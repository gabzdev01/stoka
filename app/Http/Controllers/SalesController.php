<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CreditLedger;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        if (!session('shift_id')) {
            $hour     = now()->hour;
            $greeting = match (true) {
                $hour < 12 => 'Good morning',
                $hour < 17 => 'Good afternoon',
                default    => 'Good evening',
            };

            return view('sales.open-shift', [
                'greeting' => $greeting,
            ]);
        }

        $products = Product::where('status', 'active')
            ->with([
                'variants' => fn($q) => $q->orderByRaw("FIELD(size,'S','M','L','XL','XXL') + 0, size ASC"),
                'bottles'  => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1),
            ])
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('sales.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'      => 'required|integer|exists:products,id',
            'variant_id'      => 'nullable|integer|exists:product_variants,id',
            'quantity_or_ml'  => 'required|numeric|min:0.01',
            'actual_price'    => 'required|numeric|min:0',
            'payment_type'    => 'required|in:cash,mpesa,credit',
            'customer_name'   => 'nullable|string|max:100',
            'customer_phone'  => 'nullable|string|max:20',
            'amount_paid_now' => 'nullable|numeric|min:0',
        ]);

        $shiftId = session('shift_id');
        if (!$shiftId) {
            return response()->json(['error' => 'No active shift.'], 422);
        }

        $shift = Shift::where('id', $shiftId)->where('status', 'open')->first();
        if (!$shift) {
            return response()->json(['error' => 'Shift is not open.'], 422);
        }

        $product = Product::with([
            'variants',
            'bottles' => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1),
        ])->findOrFail($data['product_id']);

        $staffId = session('auth_user');
        $qty     = (float) $data['quantity_or_ml'];
        $price   = (float) $data['actual_price'];
        $total   = round($qty * $price, 2);

        try {
            $result = DB::transaction(function () use ($data, $product, $shift, $staffId, $qty, $price, $total) {

                $bottleId  = null;
                $variantId = null;
                $newStock  = null;

                // ── Unit: decrement product stock ─────────────────────
                if ($product->type === 'unit') {
                    if ($product->track_stock && $product->stock < $qty) {
                        throw new \Exception('Insufficient stock.');
                    }
                    if ($product->track_stock) {
                        $product->decrement('stock', $qty);
                        $newStock = $product->stock - $qty;
                    }
                }

                // ── Variant: decrement variant stock ──────────────────
                if ($product->type === 'variant') {
                    $variant = $product->variants->find($data['variant_id'] ?? null);
                    if (!$variant) {
                        throw new \Exception('Variant not found.');
                    }
                    if ($product->track_stock && $variant->stock < $qty) {
                        throw new \Exception('Insufficient stock for this size.');
                    }
                    if ($product->track_stock) {
                        $variant->decrement('stock', $qty);
                    }
                    $variantId = $variant->id;
                    $newStock  = $variant->stock - $qty;
                }

                // ── Measured: decrement bottle ml ─────────────────────
                if ($product->type === 'measured') {
                    $bottle = $product->bottles->first();
                    if (!$bottle) {
                        throw new \Exception('No active bottle found.');
                    }
                    if ($bottle->remaining_ml < $qty) {
                        throw new \Exception('Not enough ml remaining in bottle.');
                    }
                    $bottle->decrement('remaining_ml', $qty);
                    $bottleId = $bottle->id;
                    $newStock = $bottle->remaining_ml - $qty;
                }

                // ── Create sale record ────────────────────────────────
                $sale = Sale::create([
                    'shift_id'       => $shift->id,
                    'staff_id'       => $staffId,
                    'product_id'     => $product->id,
                    'variant_id'     => $variantId,
                    'bottle_id'      => $bottleId,
                    'customer_id'    => null,
                    'quantity_or_ml' => $qty,
                    'unit_price'     => $product->shelf_price,
                    'actual_price'   => $price,
                    'total'          => $total,
                    'payment_type'   => $data['payment_type'],
                ]);

                // ── Credit: create customer + ledger (supports partial payment) ──
                if ($data['payment_type'] === 'credit') {
                    $amountPaidNow = (float) ($data['amount_paid_now'] ?? 0);
                    $amountPaidNow = min($amountPaidNow, $total);
                    $creditBalance = round($total - $amountPaidNow, 2);

                    $customer = Customer::firstOrCreate(
                        ['phone' => $data['customer_phone'] ?? null],
                        ['name'  => $data['customer_name']  ?? 'Unknown']
                    );
                    if (!empty($data['customer_name'])) {
                        $customer->update(['name' => $data['customer_name']]);
                    }
                    $sale->update(['customer_id' => $customer->id]);

                    CreditLedger::create([
                        'customer_id' => $customer->id,
                        'sale_id'     => $sale->id,
                        'amount'      => $total,
                        'paid'        => $amountPaidNow,
                        'balance'     => $creditBalance,
                        'status'      => $creditBalance <= 0 ? 'settled' : 'open',
                    ]);

                    // Outstanding tracks only the unpaid remainder
                    $customer->increment('total_outstanding', $creditBalance);
                }

                // ── M-Pesa: accumulate on shift ───────────────────────
                if ($data['payment_type'] === 'mpesa') {
                    $shift->increment('mpesa_total', $total);
                }

                return [
                    'sale_id'      => $sale->id,
                    'product_id'   => $product->id,
                    'product_type' => $product->type,
                    'variant_id'   => $variantId,
                    'product_name' => $product->name,
                    'total'        => $total,
                    'payment_type' => $data['payment_type'],
                    'new_stock'    => $newStock,
                ];
            });

            return response()->json(['success' => true] + $result);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function activeShift()
    {
        $shift      = null;
        $allSales   = collect();
        $totalSales = 0;
        $cashTotal  = 0;
        $mpesaTotal = 0;

        $shiftId = session('shift_id');
        if ($shiftId) {
            $shift = Shift::where('id', $shiftId)
                ->where('status', 'open')
                ->with(['staff',
                    'sales' => fn($q) => $q
                        ->with(['product', 'variant'])
                        ->orderByDesc('created_at'),
                ])
                ->first();

            if ($shift) {
                $allSales   = $shift->sales;
                $active     = $allSales->whereNull('voided_at');
                $totalSales = $active->sum('total');
                $cashTotal  = $active->where('payment_type', 'cash')->sum('total');
                $mpesaTotal = $active->where('payment_type', 'mpesa')->sum('total');
            }
        }

        return view('sales.shift', compact(
            'shift', 'allSales', 'totalSales', 'cashTotal', 'mpesaTotal'
        ));
    }

    public function history()
    {
        $staffId = session('auth_user');
        $shifts  = Shift::where('staff_id', $staffId)
            ->where('status', 'closed')
            ->with([
                'sales' => fn($q) => $q
                    ->with(['product', 'variant'])
                    ->orderByDesc('created_at'),
            ])
            ->orderByDesc('closed_at')
            ->get();

        return view('sales.history', compact('shifts'));
    }

    public function void(Request $request, Sale $sale)
    {
        $request->validate([
            'void_reason' => ['required', 'string', 'max:255'],
        ]);

        $shiftId = session('shift_id');

        if (!$shiftId || $sale->shift_id != $shiftId) {
            return response()->json(['error' => 'Sale does not belong to your current shift.'], 403);
        }

        $shift = Shift::find($shiftId);
        if (!$shift || $shift->status !== 'open') {
            return response()->json(['error' => 'Shift is not open.'], 403);
        }

        if ($sale->voided_at) {
            return response()->json(['error' => 'Sale is already voided.'], 422);
        }

        $sale->update([
            'voided_at'   => now(),
            'void_reason' => $request->input('void_reason'),
        ]);

        // Reverse M-Pesa total on shift
        if ($sale->payment_type === 'mpesa') {
            $shift->decrement('mpesa_total', $sale->total);
        }

        // Reverse unpaid credit balance
        if ($sale->payment_type === 'credit') {
            $ledger = $sale->creditLedger;
            if ($ledger && $ledger->balance > 0) {
                $outstanding = $ledger->balance;
                $ledger->update(['status' => 'settled', 'balance' => 0]);
                $sale->customer?->decrement('total_outstanding', $outstanding);
            }
        }

        return response()->json(['success' => true, 'total' => (float) $sale->total]);
    }

    public function storeCart(Request $request)
    {
        $data = $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.product_id'     => 'required|integer|exists:products,id',
            'items.*.variant_id'     => 'nullable|integer|exists:product_variants,id',
            'items.*.bottle_id'      => 'nullable|integer|exists:product_bottles,id',
            'items.*.quantity_or_ml' => 'required|numeric|min:0.01',
            'items.*.actual_price'   => 'required|numeric|min:0',
            'items.*.unit_price'     => 'required|numeric|min:0',
            'payment_type'    => 'required|in:cash,mpesa,credit',
            'customer_name'   => 'nullable|string|max:100',
            'customer_phone'  => 'nullable|string|max:20',
            'amount_paid_now' => 'nullable|numeric|min:0',
        ]);

        $shiftId = session('shift_id');
        if (!$shiftId) {
            return response()->json(['error' => 'No active shift.'], 422);
        }

        $shift = Shift::where('id', $shiftId)->where('status', 'open')->first();
        if (!$shift) {
            return response()->json(['error' => 'Shift is not open.'], 422);
        }

        $staffId     = session('auth_user');
        $paymentType = $data['payment_type'];
        $cartItems   = $data['items'];

        try {
            $result = DB::transaction(function () use ($data, $cartItems, $shift, $staffId, $paymentType) {

                $stockUpdates = [];
                $grandTotal    = 0;
                $saleIds       = [];
                $receiptItems  = [];
                $customerId    = null;

                // ── Resolve customer once for credit sales ────────────
                if ($paymentType === 'credit' && !empty($data['customer_phone'])) {
                    $customer = Customer::firstOrCreate(
                        ['phone' => $data['customer_phone']],
                        ['name'  => $data['customer_name'] ?? 'Unknown']
                    );
                    if (!empty($data['customer_name'])) {
                        $customer->update(['name' => $data['customer_name']]);
                    }
                    $customerId = $customer->id;
                }

                // ── Process each cart item ────────────────────────────
                foreach ($cartItems as $item) {
                    $qty   = (float) $item['quantity_or_ml'];
                    $price = (float) $item['actual_price'];
                    $total = round($qty * $price, 2);
                    $grandTotal += $total;

                    $product = Product::with([
                        'variants',
                        'bottles' => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1),
                    ])->findOrFail($item['product_id']);

                    $variantId = null;
                    $bottleId  = null;
                    $newStock  = null;

                    if ($product->type === 'unit') {
                        if ($product->track_stock && $product->stock < $qty) {
                            throw new \Exception("Insufficient stock for {$product->name}.");
                        }
                        if ($product->track_stock) {
                            $product->decrement('stock', $qty);
                            $newStock = (float)$product->fresh()->stock;
                        }
                    }

                    if ($product->type === 'variant') {
                        $variant = $product->variants->find($item['variant_id'] ?? null);
                        if (!$variant) {
                            throw new \Exception("Variant not found for {$product->name}.");
                        }
                        if ($product->track_stock && $variant->stock < $qty) {
                            throw new \Exception("Insufficient stock for {$product->name} ({$variant->size}).");
                        }
                        if ($product->track_stock) {
                            $variant->decrement('stock', $qty);
                            $newStock = (float)$variant->fresh()->stock;
                        }
                        $variantId = $variant->id;
                    }

                    if ($product->type === 'measured') {
                        $bottle = $product->bottles->first();
                        if (!$bottle) {
                            throw new \Exception("No active bottle for {$product->name}.");
                        }
                        if ($bottle->remaining_ml < $qty) {
                            throw new \Exception("Not enough ml remaining for {$product->name}.");
                        }
                        $bottle->decrement('remaining_ml', $qty);
                        $bottleId = $bottle->id;
                        $newStock = (float)$bottle->fresh()->remaining_ml;
                    }

                    $sale = Sale::create([
                        'shift_id'       => $shift->id,
                        'staff_id'       => $staffId,
                        'product_id'     => $product->id,
                        'variant_id'     => $variantId,
                        'bottle_id'      => $bottleId,
                        'customer_id'    => $customerId,
                        'quantity_or_ml' => $qty,
                        'unit_price'     => (float) $item['unit_price'],
                        'actual_price'   => $price,
                        'total'          => $total,
                        'payment_type'   => $paymentType,
                    ]);

                    $saleIds[] = $sale->id;

                    $receiptItems[] = [
                        'name'    => $product->name,
                        'variant' => $variantId
                            ? (optional($product->variants->find($variantId))->size)
                            : null,
                        'qty'     => $qty,
                        'price'   => $price,
                        'total'   => $total,
                    ];

                    $stockUpdates[] = [
                        'product_id' => $product->id,
                        'variant_id' => $variantId,
                        'bottle_id'  => $bottleId,
                        'new_stock'  => $newStock,
                    ];
                }

                $grandTotal = round($grandTotal, 2);

                // ── Credit: one ledger entry covering full cart total ──
                if ($paymentType === 'credit') {
                    $amountPaidNow = (float) ($data['amount_paid_now'] ?? 0);
                    $amountPaidNow = min($amountPaidNow, $grandTotal);
                    $creditBalance = round($grandTotal - $amountPaidNow, 2);

                    if ($customerId) {
                        $customer = Customer::find($customerId);
                        CreditLedger::create([
                            'customer_id' => $customerId,
                            'sale_id'     => $saleIds[0],   // anchor to first sale
                            'amount'      => $grandTotal,
                            'paid'        => $amountPaidNow,
                            'balance'     => $creditBalance,
                            'status'      => $creditBalance <= 0 ? 'settled' : 'open',
                        ]);
                        $customer->increment('total_outstanding', $creditBalance);
                    }
                }

                // ── M-Pesa: accumulate on shift ───────────────────────
                if ($paymentType === 'mpesa') {
                    $shift->increment('mpesa_total', $grandTotal);
                }

                return [
                    'items_count'   => count($saleIds),
                    'total'         => $grandTotal,
                    'payment_type'  => $paymentType,
                    'stock_updates' => $stockUpdates,
                    'sale_ids'      => $saleIds,
                    'receipt_items' => $receiptItems,
                ];
            });

            $customerPhone = $paymentType === 'credit' ? ($data['customer_phone'] ?? null) : null;
            $customerName  = $paymentType === 'credit' ? ($data['customer_name']  ?? null) : null;
            return response()->json(array_merge(
                ['success' => true, 'customer_phone' => $customerPhone, 'customer_name' => $customerName],
                $result
            ));

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private static function buildCreditSaleMessage(array $wac): string
    {
        $firstName = explode(' ', trim($wac['name'] ?? 'there'))[0];
        $shopName  = tenant('name') ?? 'the shop';
        $nf        = fn(float $v) => 'Ksh ' . number_format($v, 0);

        $lines   = [];
        $lines[] = 'Hi ' . $firstName . '! ✓';
        $lines[] = '';
        $lines[] = 'Your credit at ' . $shopName . ' has been recorded.';
        $lines[] = 'Amount:       ' . $nf((float) $wac['amount']);
        if ((float) $wac['paid'] > 0) {
            $lines[] = 'Paid now:     ' . $nf((float) $wac['paid']);
        }
        $lines[] = 'Balance owed: ' . $nf((float) $wac['balance']);
        $lines[] = '';
        $lines[] = 'Please settle when convenient. Thank you!';
        $lines[] = $shopName . ' · Powered by Stoka';

        return implode("\n", $lines);
    }

    public function customerLookup(Request $request)
    {
        $phone = trim($request->query('phone', ''));

        if (strlen($phone) < 7) {
            return response()->json([]);
        }

        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            return response()->json([]);
        }

        return response()->json([
            'id'    => $customer->id,
            'name'  => $customer->name,
            'phone' => $customer->phone,
        ]);
    }

    public function receipt(string $ids)
    {
        $idArray = array_values(array_filter(array_map('intval', explode(',', $ids))));
        if (empty($idArray)) {
            abort(404);
        }
        $sales = Sale::with(['product', 'variant', 'staff'])
            ->whereIn('id', $idArray)
            ->get();
        if ($sales->isEmpty()) {
            abort(404);
        }
        return view('sales.receipt', [
            'sales' => $sales,
        ]);
    }
}
