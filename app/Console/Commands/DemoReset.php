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

    /**
     * Restore stock only — called on every shift open in the demo tenant.
     * Safe to call while tenancy is already initialised.
     */
    public static function restoreStock(): void
    {
        DB::table('products')
            ->where('type', 'unit')
            ->whereIn('id', array_keys(self::UNIT_STOCK))
            ->get()
            ->each(function ($p) {
                DB::table('products')->where('id', $p->id)->update([
                    'stock'                => self::UNIT_STOCK[$p->id],
                    'low_stock_alert_sent' => false,
                ]);
            });

        foreach (self::VARIANT_STOCK as $variantId => $stock) {
            DB::table('product_variants')
                ->where('id', $variantId)
                ->update(['stock' => $stock]);
        }

        DB::table('products')
            ->whereIn('type', ['variant', 'measured'])
            ->update(['low_stock_alert_sent' => false]);

        foreach (self::BOTTLE_ML as $bottleId => $ml) {
            DB::table('product_bottles')
                ->where('id', $bottleId)
                ->update(['remaining_ml' => $ml, 'active' => true]);
        }
    }

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
                'exchanges',
                'credit_payments',
                'credit_ledger',
                'customers',
                'sales',
                'shifts',
            ] as $table) {
                DB::table($table)->truncate();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // ── Restore all stock ─────────────────────────────────────────
            self::restoreStock();

            // ── Clear tenant-level transient fields ───────────────────────
            $tenant->update(['wa_sent_at' => null]);

            // -- Re-seed transactional demo data ----------------------
            self::seedDemoData();

        } finally {
            tenancy()->end();
        }

        $this->info('Demo tenant reset complete — ' . now()->toDateTimeString());
        return Command::SUCCESS;
    }

    public static function seedDemoData(): void
    {
        $today = \Carbon\Carbon::now()->startOfDay();

        $salesPerShift = [
            [[57,850,850,1,'cash',6,10],[57,850,850,1,'mpesa',6,11],[60,150,150,4,'cash',6,12],[55,4500,4500,1,'mpesa',6,14],[49,3200,2900,1,'cash',6,15]],
            [[57,850,850,2,'cash',5,11],[60,150,150,2,'cash',5,13],[56,5800,5500,1,'mpesa',5,14],[53,2200,2200,1,'cash',5,16]],
            [[56,5800,5800,1,'mpesa',3,11],[55,4500,4500,1,'mpesa',3,12],[57,850,850,3,'cash',3,13],[54,3800,3600,1,'cash',3,15],[51,2800,2800,1,'mpesa',3,16]],
            [[57,850,850,1,'cash',1,10],[60,150,150,3,'mpesa',1,11],[52,2600,2600,1,'cash',1,13],[50,2400,2400,1,'credit',1,15]],
        ];
        $shiftDefs = [
            [2, 6, 9, 0,  18, 15],
            [3, 5, 9, 0,  17, 30],
            [2, 3, 10, -150, 19, 0],
            [3, 1, 9, 0,  17, 0],
        ];

        foreach ($shiftDefs as $i => [$staffId, $daysAgo, $openH, $disc, $closeH, $closeM]) {
            $open  = $today->copy()->subDays($daysAgo)->setHour($openH)->setMinute(0);
            $close = $today->copy()->subDays($daysAgo)->setHour($closeH)->setMinute($closeM);
            $cash = $mpesa = 0;
            foreach ($salesPerShift[$i] as [$pid,$unit,$actual,$qty,$type]) {
                if ($type === 'cash')  $cash  += $actual * $qty;
                if ($type === 'mpesa') $mpesa += $actual * $qty;
            }
            $expected = 500 + $cash;
            $shiftId = DB::table('shifts')->insertGetId([
                'staff_id'=>$staffId,'opened_at'=>$open,'closed_at'=>$close,
                'opening_float'=>500,'cash_counted'=>$expected+$disc,
                'mpesa_total'=>$mpesa,'expected_cash'=>$expected,
                'cash_discrepancy'=>$disc,'status'=>'closed',
                'created_at'=>$open,'updated_at'=>$close,
            ]);
            foreach ($salesPerShift[$i] as [$pid,$unit,$actual,$qty,$type,$dAgo,$hour]) {
                $at = $today->copy()->subDays($dAgo)->setHour($hour);
                DB::table('sales')->insert([
                    'shift_id'=>$shiftId,'staff_id'=>$staffId,'product_id'=>$pid,
                    'variant_id'=>null,'bottle_id'=>null,'customer_id'=>null,
                    'quantity_or_ml'=>$qty,'unit_price'=>$unit,'actual_price'=>$actual,
                    'total'=>$actual*$qty,'payment_type'=>$type,
                    'voided_at'=>null,'created_at'=>$at,'updated_at'=>$at,
                ]);
            }
        }

        // Open shift today — James (staff) · 4 sales · KES 7,850
        $openShiftId = DB::table('shifts')->insertGetId([
            'staff_id'=>2,'opened_at'=>$today->copy()->setHour(9),'closed_at'=>null,
            'opening_float'=>500,'cash_counted'=>0,'mpesa_total'=>0,
            'expected_cash'=>500,'cash_discrepancy'=>0,'status'=>'open',
            'created_at'=>$today->copy()->setHour(9),'updated_at'=>$today->copy()->setHour(9),
        ]);
        foreach ([
            [57, 850, 850,1,'cash', $today->copy()->setHour(9)->setMinute(32)],
            [60, 150, 150,2,'cash', $today->copy()->setHour(10)->setMinute(15)],
            [55,4500,4500,1,'mpesa',$today->copy()->setHour(11)->setMinute(0)],
            [53,2200,2200,1,'mpesa',$today->copy()->setHour(11)->setMinute(48)],
        ] as [$pid,$unit,$actual,$qty,$type,$at]) {
            DB::table('sales')->insert([
                'shift_id'=>$openShiftId,'staff_id'=>2,'product_id'=>$pid,
                'variant_id'=>null,'bottle_id'=>null,'customer_id'=>null,
                'quantity_or_ml'=>$qty,'unit_price'=>$unit,'actual_price'=>$actual,
                'total'=>$actual*$qty,'payment_type'=>$type,
                'voided_at'=>null,'created_at'=>$at,'updated_at'=>$at,
            ]);
        }

        // Customers + credit
        $c1 = DB::table('customers')->insertGetId(['name'=>'Brenda Achieng','phone'=>'0712345678','total_outstanding'=>2400,'created_at'=>$today->copy()->subDays(1),'updated_at'=>$today->copy()->subDays(1)]);
        $c2 = DB::table('customers')->insertGetId(['name'=>'Susan Wanjiku','phone'=>'0723456789','total_outstanding'=>850,'created_at'=>$today->copy()->subDays(3),'updated_at'=>$today->copy()->subDays(3)]);
        $creditSale = DB::table('sales')->where('payment_type','credit')->where('product_id',50)->first();
        if ($creditSale) {
            DB::table('sales')->where('id',$creditSale->id)->update(['customer_id'=>$c1]);
            DB::table('credit_ledger')->insert(['customer_id'=>$c1,'sale_id'=>$creditSale->id,'amount'=>2400,'paid'=>0,'balance'=>2400,'last_payment_at'=>null,'status'=>'open','created_at'=>$today->copy()->subDays(1),'updated_at'=>$today->copy()->subDays(1)]);
        }
        DB::table('credit_ledger')->insert(['customer_id'=>$c2,'sale_id'=>null,'amount'=>850,'paid'=>0,'balance'=>850,'last_payment_at'=>null,'status'=>'open','created_at'=>$today->copy()->subDays(3),'updated_at'=>$today->copy()->subDays(3)]);

        // Supplier balance
        DB::table('supplier_balances')->insert(['supplier_id'=>2,'restock_id'=>null,'total_cost'=>18500,'amount_paid'=>10000,'balance'=>8500,'settled_at'=>null,'created_at'=>$today->copy()->subDays(5),'updated_at'=>$today->copy()->subDays(5)]);
    }
}
