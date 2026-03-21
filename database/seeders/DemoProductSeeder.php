<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductBottle;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoProductSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ensure Abdi Warsame supplier exists ───────────────────
        $abdi = Supplier::firstOrCreate(
            ['name' => 'Abdi Warsame'],
            ['phone' => '0722000111', 'notes' => 'Perfumes & fragrances']
        );

        // ── Unit products ─────────────────────────────────────────
        // Uses updateOrCreate by name so re-running is safe
        $unitProducts = [
            // Dresses
            ['name' => 'Dress',               'category' => 'Dresses', 'shelf_price' => 1500, 'stock' => 12, 'threshold' => 3],
            ['name' => 'Kitenge Wrap Dress',   'category' => 'Dresses', 'shelf_price' => 2400, 'stock' => 6,  'threshold' => 2],
            ['name' => 'Ankara Floral Dress',  'category' => 'Dresses', 'shelf_price' => 1800, 'stock' => 11, 'threshold' => 3, 'dead_stock' => true],
            ['name' => 'Silk Dera',            'category' => 'Dresses', 'shelf_price' => 1600, 'stock' => 7,  'threshold' => 2],
            ['name' => 'Shorts',               'category' => 'Dresses', 'shelf_price' => 800,  'stock' => 15, 'threshold' => 4],
            // Tops
            ['name' => 'Plain T-shirt',        'category' => 'Tops',    'shelf_price' => 550,  'stock' => 22, 'threshold' => 5],
            ['name' => 'Polo T-shirt',         'category' => 'Tops',    'shelf_price' => 950,  'stock' => 10, 'threshold' => 3],
            ['name' => 'Vest',                 'category' => 'Tops',    'shelf_price' => 350,  'stock' => 20, 'threshold' => 5],
            // Bottoms
            ['name' => 'Jeans',                'category' => 'Bottoms', 'shelf_price' => 2200, 'stock' => 9,  'threshold' => 3],
            ['name' => 'Boxers (3-pack)',       'category' => 'Bottoms', 'shelf_price' => 500,  'stock' => 18, 'threshold' => 5],
            // Shoes
            ['name' => 'Shoes',                'category' => 'Shoes',   'shelf_price' => 2500, 'stock' => 8,  'threshold' => 2],
            // Bags
            ['name' => 'Braided Leather Tote', 'category' => 'Bags',    'shelf_price' => 2800, 'stock' => 5,  'threshold' => 2],
            ['name' => 'Handbag',              'category' => 'Bags',    'shelf_price' => 1800, 'stock' => 4,  'threshold' => 2],
            ['name' => 'Wallet',               'category' => 'Bags',    'shelf_price' => 800,  'stock' => 11, 'threshold' => 3],
            ['name' => 'Belt',                 'category' => 'Bags',    'shelf_price' => 600,  'stock' => 14, 'threshold' => 4],
            // Beauty
            ['name' => 'Body Splash',          'category' => 'Beauty',  'shelf_price' => 450,  'stock' => 8,  'threshold' => 3],
            ['name' => 'Body Scrub',           'category' => 'Beauty',  'shelf_price' => 600,  'stock' => 5,  'threshold' => 2, 'dead_stock' => true],
            ['name' => 'Lipstick',             'category' => 'Beauty',  'shelf_price' => 300,  'stock' => 16, 'threshold' => 5],
            ['name' => 'Lip Gloss',            'category' => 'Beauty',  'shelf_price' => 250,  'stock' => 12, 'threshold' => 4],
            ['name' => 'Eye Pencil',           'category' => 'Beauty',  'shelf_price' => 150,  'stock' => 20, 'threshold' => 6],
            ['name' => 'Hair Extensions',      'category' => 'Beauty',  'shelf_price' => 1200, 'stock' => 6,  'threshold' => 2],
        ];

        foreach ($unitProducts as $p) {
            $product = Product::updateOrCreate(
                ['name' => $p['name']],
                [
                    'category'           => $p['category'],
                    'type'               => 'unit',
                    'shelf_price'        => $p['shelf_price'],
                    'stock'              => $p['stock'],
                    'low_stock_threshold'=> $p['threshold'],
                    'status'             => 'active',
                    'track_stock'        => true,
                ]
            );

            // Dead stock: push updated_at back 45 days so products controller
            // can detect no recent sales (or use this as a proxy indicator)
            if (!empty($p['dead_stock'])) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['updated_at' => now()->subDays(45)]);
            }
        }

        // ── Measured perfumes (supplier: Abdi Warsame) ────────────
        $perfumes = [
            // name, price_per_ml, total_ml, remaining_ml
            ['name' => 'One Million', 'ppm' => 15, 'total' => 500, 'rem' => 380],
            ['name' => 'Polo Sport',  'ppm' => 12, 'total' => 500, 'rem' => 420],
            ['name' => 'Invictus',    'ppm' => 14, 'total' => 500, 'rem' => 310],
            ['name' => '212 VIP',     'ppm' => 13, 'total' => 500, 'rem' => 85],  // running low (<20%)
            ['name' => 'Polo Blue',   'ppm' => 11, 'total' => 500, 'rem' => 460],
            ['name' => 'Dunhill',     'ppm' => 10, 'total' => 500, 'rem' => 500],
        ];

        foreach ($perfumes as $p) {
            $product = Product::updateOrCreate(
                ['name' => $p['name']],
                [
                    'supplier_id'        => $abdi->id,
                    'category'           => 'Perfumes',
                    'type'               => 'measured',
                    'shelf_price'        => $p['ppm'], // per-ml price stored as shelf_price
                    'stock'              => 0,
                    'low_stock_threshold'=> 0,
                    'status'             => 'active',
                    'track_stock'        => false,
                ]
            );

            // Update or create the bottle record
            ProductBottle::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'total_ml'     => $p['total'],
                    'remaining_ml' => $p['rem'],
                    'price_per_ml' => $p['ppm'],
                    'active'       => true,
                ]
            );
        }

        $this->command->info('Done. ' . count($unitProducts) . ' unit products + ' . count($perfumes) . ' perfumes seeded.');
    }
}
