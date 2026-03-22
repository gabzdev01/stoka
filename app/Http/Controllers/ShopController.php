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

        // Base query
        $query = Product::where('shop_visible', true)
            ->where('status', 'active')
            ->with(['variants', 'bottles' => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1)]);

        // Search filter
        $searchTerm = $request->query('q');
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sorting
        $sort = $request->query('sort', 'category');
        match($sort) {
            'price-asc'  => $query->orderBy('shelf_price', 'asc'),
            'price-desc' => $query->orderBy('shelf_price', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->orderBy('category')->orderBy('name'),
        };

        $products   = $query->get();
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
