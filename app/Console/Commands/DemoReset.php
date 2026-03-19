<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DemoReset extends Command
{
    protected $signature   = 'demo:reset';
    protected $description = 'Reset the demo tenant to a clean seed state (safe to run daily)';

    // ── Stock seed values ─────────────────────────────────────────────────────
    // Unit products: [ product_id => stock ]
    private const UNIT_STOCK = [
        7 => 5,   // Woven Leather Tote
        8 => 5,   // Bucket Hat
        9 => 8,   // Vanilla & Musk Body Spray
    ];

    // Variants: [ variant_id => stock ]
    private const VARIANT_STOCK = [
        26 => 3, 27 => 3, 28 => 3, 29 => 2,   // Ankara Floral Dress S/M/L/XL
         8 => 2,  9 => 2, 10 => 2,             // Kitenge Wrap Dress S/M/L
        11 => 3, 12 => 5, 13 => 4, 14 => 2,   // Men's Linen Shirt S/M/L/XL
        15 => 2, 16 => 4, 17 => 3, 18 => 2,   // Denim Mom Jeans 28/30/32/34
        19 => 3, 20 => 4, 21 => 3,             // Striped Polo Shirt S/M/L
    ];

    // Bottles: [ bottle_id => remaining_ml ]  (restore to total_ml)
    private const BOTTLE_ML = [
        1 => 500.00,   // Baccarat Rouge Inspired
        2 => 300.00,   // Oud & Amber Blend
    ];

    public function handle(): int
    {
        $tenant = Tenant::find('demo');

        if (! $tenant) {
            $this->error('Demo tenant not found — aborting.');
            return Command::FAILURE;
        }

        tenancy()->initialize($tenant);

        try {
            // ── Truncate transactional tables ─────────────────────────────
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ([
                'shopping_list_items',
                'shopping_lists',
                'supplier_payments',
                'supplier_balances',
                'restock_items',
                'restocks',
                'credit_ledger',
                'customers',
                'sales',
                'shifts',
            ] as $table) {
                DB::table($table)->truncate();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // ── Restore unit product stock ────────────────────────────────
            foreach (self::UNIT_STOCK as $productId => $stock) {
                DB::table('products')
                    ->where('id', $productId)
                    ->update([
                        'stock'                => $stock,
                        'low_stock_alert_sent' => false,
                    ]);
            }

            // ── Restore variant stock ─────────────────────────────────────
            foreach (self::VARIANT_STOCK as $variantId => $stock) {
                DB::table('product_variants')
                    ->where('id', $variantId)
                    ->update(['stock' => $stock]);
            }

            DB::table('products')
                ->whereIn('type', ['variant', 'measured'])
                ->update(['low_stock_alert_sent' => false]);

            // ── Restore bottle ml ─────────────────────────────────────────
            foreach (self::BOTTLE_ML as $bottleId => $ml) {
                DB::table('product_bottles')
                    ->where('id', $bottleId)
                    ->update(['remaining_ml' => $ml, 'active' => true]);
            }

            // ── Clear tenant-level transient fields ───────────────────────
            $tenant->update(['wa_sent_at' => null]);

        } finally {
            tenancy()->end();
        }

        $this->info('Demo tenant reset complete — ' . now()->toDateTimeString());
        return Command::SUCCESS;
    }
}
