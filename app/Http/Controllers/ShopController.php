<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $tenant = tenant();

        if (!$tenant || !$tenant->shop_enabled) {
            return view('shop.closed', compact('tenant'));
        }

        $products = Product::where('shop_visible', true)
            ->where('status', 'active')
            ->with(['variants', 'bottles' => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1)])
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $categories = $products->pluck('category')->filter()->unique()->sort()->values();
        $filterCat  = $request->query('cat');
        $isDemo     = $tenant->id === 'demo';

        return view('shop.index', compact('tenant', 'products', 'categories', 'filterCat', 'isDemo'));
    }

    public function show(Product $product)
    {
        $tenant = tenant();

        if (!$tenant || !$tenant->shop_enabled || !$product->shop_visible || $product->status !== 'active') {
            abort(404);
        }

        $product->load(['variants', 'bottles' => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1)]);

        $isDemo = $tenant->id === 'demo';

        return view('shop.show', compact('tenant', 'product', 'isDemo'));
    }
}
