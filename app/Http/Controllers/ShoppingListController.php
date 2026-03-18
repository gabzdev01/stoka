<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class ShoppingListController extends Controller
{
    public function index()
    {
        // 30-day sales velocity per product
        $velocity = DB::table('sales')
            ->selectRaw('product_id, SUM(quantity_or_ml) as sold_30d')
            ->whereNull('voided_at')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('product_id')
            ->pluck('sold_30d', 'product_id');

        // 30-day variant-level velocity
        $variantVelocity = DB::table('sales')
            ->selectRaw('variant_id, SUM(quantity_or_ml) as sold_30d')
            ->whereNull('voided_at')
            ->whereNotNull('variant_id')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('variant_id')
            ->pluck('sold_30d', 'variant_id');

        $products = Product::where('status', 'active')
            ->with([
                'supplier',
                'variants' => fn($q) => $q->orderByRaw("FIELD(size,'S','M','L','XL','XXL') + 0, size ASC"),
                'bottles'  => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1),
            ])
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Attach velocity and build stock status per product
        foreach ($products as $product) {
            $v30 = (float) ($velocity[$product->id] ?? 0);
            $product->setAttribute('sold_30d', $v30);

            if ($product->type === 'unit') {
                $stock     = (int) $product->stock;
                $threshold = (int) $product->low_stock_threshold;
                $suggest   = max(0, (int) ceil($v30 * 1.5) - $stock);

                $product->setAttribute('stock_status',
                    $stock === 0 ? 'out' : ($stock <= $threshold ? 'low' : 'ok'));
                $product->setAttribute('suggest_qty', $suggest);

            } elseif ($product->type === 'variant') {
                foreach ($product->variants as $variant) {
                    $vv30    = (float) ($variantVelocity[$variant->id] ?? 0);
                    $vstock  = (int) $variant->stock;
                    $suggest = max(0, (int) ceil($vv30 * 1.5) - $vstock);
                    $threshold = (int) $product->low_stock_threshold;

                    $variant->setAttribute('sold_30d', $vv30);
                    $variant->setAttribute('suggest_qty', $suggest);
                    $variant->setAttribute('stock_status',
                        $vstock === 0 ? 'out' : ($vstock <= $threshold ? 'low' : 'ok'));
                }
                // Product-level status = worst variant
                $statuses = $product->variants->pluck('stock_status');
                $product->setAttribute('stock_status',
                    $statuses->contains('out') ? 'out' :
                    ($statuses->contains('low') ? 'low' : 'ok'));
                $product->setAttribute('suggest_qty',
                    $product->variants->sum('suggest_qty'));

            } elseif ($product->type === 'measured') {
                $bottle = $product->bottles->first();
                $pct    = $bottle ? ((float)$bottle->remaining_ml / max(1, (float)$bottle->total_ml)) : 0;
                $product->setAttribute('stock_status',
                    !$bottle || $pct == 0 ? 'out' : ($pct < 0.20 ? 'low' : 'ok'));
                $product->setAttribute('suggest_qty', 0); // bottles restocked differently
                $product->setAttribute('bottle', $bottle);
            }
        }

        // Group by category
        $byCategory = $products->groupBy('category');

        // Supplier filter list
        $suppliers = Supplier::orderBy('name')->get();

        // Summary counts
        $outCount = $products->filter(fn($p) => $p->stock_status === 'out')->count();
        $lowCount = $products->filter(fn($p) => $p->stock_status === 'low')->count();

        return view('shopping-list.index', compact(
            'products', 'byCategory', 'suppliers', 'outCount', 'lowCount'
        ));
    }
}
