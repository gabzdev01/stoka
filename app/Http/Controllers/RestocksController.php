<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBottle;
use App\Models\ProductVariant;
use App\Models\Restock;
use App\Models\RestockItem;
use App\Models\Supplier;
use App\Models\SupplierBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestocksController extends Controller
{
    public function index()
    {
        $restocks = Restock::with(['supplier', 'items', 'supplierBalance'])
            ->orderByDesc('created_at')
            ->get();

        return view('restocks.index', compact('restocks'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        $products = Product::where('status', 'active')
            ->with([
                'supplier',
                'variants' => fn($q) => $q->orderByRaw("FIELD(size,'S','M','L','XL','XXL') + 0, size ASC"),
            ])
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('restocks.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'total_cost'  => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'notes'       => 'nullable|string|max:500',
        ]);

        $supplierId = $request->supplier_id ? (int) $request->supplier_id : null;
        $totalCost  = (float) $request->total_cost;
        $amountPaid = min((float) $request->amount_paid, $totalCost);
        $items      = $request->input('items', []);

        // Filter to items with qty > 0
        $validItems = collect($items)->filter(fn($i) => (float)($i['qty'] ?? 0) > 0);

        if ($validItems->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Enter at least one item quantity.'])
                ->withInput();
        }

        DB::transaction(function () use ($supplierId, $totalCost, $amountPaid, $validItems, $request) {
            $restock = Restock::create([
                'supplier_id' => $supplierId,
                'staff_id'    => session('auth_user'),
                'notes'       => $request->notes,
            ]);

            foreach ($validItems as $item) {
                $qty        = (float)  $item['qty'];
                $costPrice  = isset($item['cost']) && $item['cost'] !== '' ? (float) $item['cost'] : null;
                $productId  = (int)   ($item['product_id'] ?? 0);
                $variantId  = !empty($item['variant_id']) ? (int) $item['variant_id'] : null;
                $isMeasured = !empty($item['measured']);

                RestockItem::create([
                    'restock_id' => $restock->id,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity'   => $qty,
                    'cost_price' => $costPrice,
                ]);

                if ($variantId) {
                    ProductVariant::where('id', $variantId)->increment('stock', $qty);
                } elseif ($isMeasured) {
                    // Deactivate current bottle and open a new one
                    ProductBottle::where('product_id', $productId)->where('active', true)
                        ->update(['active' => false]);

                    // Inherit price_per_ml from old bottle or use 0
                    $ppm = ProductBottle::where('product_id', $productId)
                        ->orderByDesc('id')
                        ->value('price_per_ml') ?? 0;

                    ProductBottle::create([
                        'product_id'   => $productId,
                        'total_ml'     => $qty,
                        'remaining_ml' => $qty,
                        'price_per_ml' => $ppm,
                        'active'       => true,
                    ]);
                } else {
                    Product::where('id', $productId)->increment('stock', $qty);
                }
            }

            if ($supplierId) {
                SupplierBalance::create([
                    'supplier_id' => $supplierId,
                    'restock_id'  => $restock->id,
                    'total_cost'  => $totalCost,
                    'amount_paid' => $amountPaid,
                    'balance'     => $totalCost - $amountPaid,
                    'settled_at'  => ($amountPaid >= $totalCost) ? now() : null,
                ]);
            }
        });

        return redirect()->route('restocks.index')
            ->with('success', 'Restock recorded. Stock levels updated.');
    }
}
