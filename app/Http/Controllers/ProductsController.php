<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with(['supplier', 'variants', 'bottles'])
            ->orderBy('name')
            ->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('products.form', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $product = Product::create([
            'supplier_id'         => $validated['supplier_id'] ?? null,
            'name'                => $validated['name'],
            'category'            => $validated['category'] ?? null,
            'gender'              => $validated['gender'] ?? null,
            'type'                => $validated['type'],
            'shelf_price'         => $validated['shelf_price'],
            'floor_price'         => $validated['floor_price'] ?? null,
            'is_bargainable'      => $request->boolean('is_bargainable'),
            'track_stock'         => $request->boolean('track_stock'),
            'stock'               => $validated['stock'] ?? 0,
            'low_stock_threshold' => $validated['low_stock_threshold'] ?? 5,
            'status'              => 'active',
        ]);

        $this->syncVariants($product, $request);
        $this->syncBottle($product, $request);

        return redirect()->route('products.index')
            ->with('success', 'Product added.');
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $product->load(['variants', 'bottles']);
        return view('products.form', compact('suppliers', 'product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request);

        $product->update([
            'supplier_id'         => $validated['supplier_id'] ?? null,
            'name'                => $validated['name'],
            'category'            => $validated['category'] ?? null,
            'gender'              => $validated['gender'] ?? null,
            'type'                => $validated['type'],
            'shelf_price'         => $validated['shelf_price'],
            'floor_price'         => $validated['floor_price'] ?? null,
            'is_bargainable'      => $request->boolean('is_bargainable'),
            'track_stock'         => $request->boolean('track_stock'),
            'stock'               => $validated['stock'] ?? 0,
            'low_stock_threshold' => $validated['low_stock_threshold'] ?? 5,
        ]);

        $this->syncVariants($product, $request);
        $this->syncBottle($product, $request);

        return redirect()->route('products.index')
            ->with('success', 'Product updated.');
    }

    public function toggle(Product $product)
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        return redirect()->route('products.index')
            ->with('success', $newStatus === 'active' ? 'Product activated.' : 'Product deactivated.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name'                  => 'required|string|max:150',
            'category'              => 'nullable|string|max:60',
            'gender'                => 'nullable|in:male,female,unisex',
            'type'                  => 'required|in:unit,measured,variant',
            'supplier_id'           => 'nullable|integer',
            'shelf_price'           => 'required|numeric|min:0',
            'floor_price'           => 'nullable|numeric|min:0',
            'track_stock'           => 'boolean',
            'stock'                 => 'nullable|integer|min:0',
            'low_stock_threshold'   => 'nullable|integer|min:0',
            'total_ml'              => 'nullable|numeric|min:0',
            'price_per_ml'          => 'nullable|numeric|min:0',
            'variants'              => 'nullable|array',
            'variants.*.size'       => 'nullable|string|max:50',
            'variants.*.colour'     => 'nullable|string|max:50',
            'variants.*.stock'      => 'nullable|integer|min:0',
        ]);
    }

    private function syncVariants(Product $product, Request $request): void
    {
        if ($product->type !== 'variant') {
            $product->variants()->delete();
            return;
        }

        $product->variants()->delete();

        foreach ($request->input('variants', []) as $v) {
            if (!empty($v['size']) || !empty($v['colour'])) {
                $product->variants()->create([
                    'size'   => $v['size'] ?? null,
                    'colour' => $v['colour'] ?? null,
                    'stock'  => (int)($v['stock'] ?? 0),
                ]);
            }
        }
    }

    private function syncBottle(Product $product, Request $request): void
    {
        if ($product->type !== 'measured') {
            return;
        }

        $totalMl = $request->input('total_ml');
        $ppm     = $request->input('price_per_ml');

        if ($totalMl && $ppm) {
            $product->bottles()->where('active', true)->delete();
            $product->bottles()->create([
                'total_ml'     => $totalMl,
                'remaining_ml' => $totalMl,
                'price_per_ml' => $ppm,
                'active'       => true,
            ]);
        }
    }
}
