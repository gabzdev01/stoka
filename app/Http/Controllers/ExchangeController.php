<?php
namespace App\Http\Controllers;

use App\Models\CreditLedger;
use App\Models\Customer;
use App\Models\Exchange;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    public function create(Sale $sale)
    {
        // Must have an open shift to record an exchange
        $shiftId = session('shift_id');
        if (!$shiftId) {
            return redirect()->route('sales.index')
                ->with('error', 'You need an open shift to record an exchange.');
        }

        if ($sale->voided_at) {
            return redirect()->route('sales.shift')
                ->with('error', 'Cannot exchange a voided sale.');
        }

        $products = Product::where('status', 'active')
            ->with([
                'variants' => fn($q) => $q->orderByRaw("FIELD(size,'S','M','L','XL','XXL') + 0, size ASC"),
                'bottles'  => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1),
            ])
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $sale->load(['product', 'variant', 'bottle']);

        return view('sales.exchange', [
            'returnedSale' => $sale,
            'products'     => $products,
        ]);
    }

    public function store(Request $request, Sale $sale)
    {
        $shiftId = session('shift_id');
        if (!$shiftId) {
            return redirect()->route('sales.index');
        }
        if ($sale->voided_at) {
            return redirect()->route('sales.shift')
                ->with('error', 'Cannot exchange a voided sale.');
        }

        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'variant_id'     => 'nullable|exists:product_variants,id',
            'quantity_or_ml' => 'required|numeric|min:0.01',
            'actual_price'   => 'required|numeric|min:0',
            'customer_name'  => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $staffId      = session('auth_user');
        $shift        = Shift::findOrFail($shiftId);
        $newProduct   = Product::with(['variants', 'bottles' => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1)])
                               ->findOrFail($request->product_id);
        $qty          = (float) $request->quantity_or_ml;
        $price        = (float) $request->actual_price;
        $newTotal     = round($qty * $price, 2);
        $returnedVal  = (float) $sale->total;
        $difference   = round($newTotal - $returnedVal, 2);

        // Policy: exchange item must be same value or more expensive
        if ($newTotal < $returnedVal) {
            return back()->withInput()->withErrors([
                'actual_price' => 'The new item total (' . tenant('currency_symbol') . ' ' . number_format($newTotal, 0) . ') must be at least ' . tenant('currency_symbol') . ' ' . number_format($returnedVal, 0) . ' (the returned item value).',
            ]);
        }

        DB::transaction(function () use (
            $request, $sale, $shift, $staffId,
            $newProduct, $qty, $price, $newTotal, $returnedVal, $difference
        ) {
            // ── 1. Void the returned sale, restore its stock ──────────────
            $sale->update([
                'voided_at'   => now(),
                'void_reason' => 'Exchanged',
            ]);

            $this->restoreStock($sale);

            // ── 2. Deduct stock for new item ──────────────────────────────
            $variantId = null;
            $bottleId  = null;

            if ($newProduct->type === 'unit') {
                if ($newProduct->track_stock) {
                    $newProduct->decrement('stock', $qty);
                }
            } elseif ($newProduct->type === 'variant') {
                $variant = $newProduct->variants->find($request->variant_id);
                if ($variant && $newProduct->track_stock) {
                    $variant->decrement('stock', $qty);
                }
                $variantId = $request->variant_id;
            } elseif ($newProduct->type === 'measured') {
                $bottle = $newProduct->bottles->first();
                if ($bottle) {
                    $bottle->decrement('remaining_ml', $qty);
                    $bottleId = $bottle->id;
                }
            }

            // ── 3. Create new sale with payment_type = 'exchange' ─────────
            $newSale = Sale::create([
                'shift_id'       => $shift->id,
                'staff_id'       => $staffId,
                'product_id'     => $newProduct->id,
                'variant_id'     => $variantId,
                'bottle_id'      => $bottleId,
                'customer_id'    => null,
                'quantity_or_ml' => $qty,
                'unit_price'     => $newProduct->shelf_price,
                'actual_price'   => $price,
                'total'          => $newTotal,
                'payment_type'   => 'exchange',
            ]);

            // ── 4. If customer owes a difference, record as credit ────────
            $customerId = null;
            if ($difference > 0 && !empty($request->customer_name)) {
                $phone    = $request->customer_phone ?? '';
                $customer = $phone
                    ? Customer::firstOrCreate(['phone' => $phone], ['name' => $request->customer_name])
                    : Customer::create(['name' => $request->customer_name, 'phone' => '']);

                if (!empty($request->customer_name)) {
                    $customer->update(['name' => $request->customer_name]);
                }

                CreditLedger::create([
                    'customer_id' => $customer->id,
                    'sale_id'     => $newSale->id,
                    'amount'      => $difference,
                    'paid'        => 0,
                    'balance'     => $difference,
                    'status'      => 'open',
                ]);

                $customer->update([
                    'total_outstanding' => CreditLedger::where('customer_id', $customer->id)
                        ->where('status', 'open')->sum('balance'),
                ]);

                $customerId = $customer->id;
                $newSale->update(['customer_id' => $customerId]);
            }

            // ── 5. Record the exchange link ───────────────────────────────
            Exchange::create([
                'shift_id'         => $shift->id,
                'staff_id'         => $staffId,
                'returned_sale_id' => $sale->id,
                'new_sale_id'      => $newSale->id,
                'customer_id'      => $customerId,
                'returned_value'   => $returnedVal,
                'new_value'        => $newTotal,
                'difference'       => $difference,
            ]);
        });

        $msg = 'Exchange recorded.';
        if ($difference > 0) {
            $msg .= ' ' . tenant('currency_symbol') . ' ' . number_format($difference, 0) . ' balance added to customer deposit.';
        }

        return redirect()->route('sales.shift')->with('success', $msg);
    }

    private function restoreStock(Sale $sale): void
    {
        $product = $sale->product;
        if (!$product || !$product->track_stock) return;

        if ($product->type === 'unit') {
            $product->increment('stock', $sale->quantity_or_ml);
        } elseif ($product->type === 'variant' && $sale->variant) {
            $sale->variant->increment('stock', $sale->quantity_or_ml);
        } elseif ($product->type === 'measured' && $sale->bottle) {
            $sale->bottle->increment('remaining_ml', $sale->quantity_or_ml);
        }
    }
}
