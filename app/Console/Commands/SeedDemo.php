<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeedDemo extends Command
{
    protected $signature   = 'demo:seed';
    protected $description = 'Seed the demo tenant with realistic transactional data';

    public function handle(): int
    {
        $tenant = \App\Models\Tenant::find('demo');
        if (!$tenant) { $this->error('Demo tenant not found'); return 1; }

        tenancy()->initialize($tenant);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['credit_payments','credit_ledger','customers','sales','shifts','supplier_payments','supplier_balances'] as $t) {
            DB::table($t)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $today = Carbon::now()->startOfDay();

        // ── Shift 1: James, 6 days ago ───────────────────────────────────────
        $this->insertShift(2, $today->copy()->subDays(6)->setHour(9), $today->copy()->subDays(6)->setHour(18)->setMinute(15), [
            [57, 850, 850,  1, 'cash',  $today->copy()->subDays(6)->setHour(10)],
            [57, 850, 850,  1, 'mpesa', $today->copy()->subDays(6)->setHour(11)],
            [60, 150, 150,  4, 'cash',  $today->copy()->subDays(6)->setHour(12)],
            [55,4500,4500,  1, 'mpesa', $today->copy()->subDays(6)->setHour(14)],
            [49,3200,2900,  1, 'cash',  $today->copy()->subDays(6)->setHour(15)],
        ], 500, 0);

        // ── Shift 2: Aisha, 5 days ago ───────────────────────────────────────
        $this->insertShift(3, $today->copy()->subDays(5)->setHour(9), $today->copy()->subDays(5)->setHour(17)->setMinute(30), [
            [57, 850, 850,  2, 'cash',  $today->copy()->subDays(5)->setHour(11)],
            [60, 150, 150,  2, 'cash',  $today->copy()->subDays(5)->setHour(13)],
            [56,5800,5500,  1, 'mpesa', $today->copy()->subDays(5)->setHour(14)],
            [53,2200,2200,  1, 'cash',  $today->copy()->subDays(5)->setHour(16)],
        ], 500, 0);

        // ── Shift 3: James, 3 days ago — best + discrepancy ─────────────────
        $this->insertShift(2, $today->copy()->subDays(3)->setHour(10), $today->copy()->subDays(3)->setHour(19), [
            [56,5800,5800,  1, 'mpesa', $today->copy()->subDays(3)->setHour(11)],
            [55,4500,4500,  1, 'mpesa', $today->copy()->subDays(3)->setHour(12)],
            [57, 850, 850,  3, 'cash',  $today->copy()->subDays(3)->setHour(13)],
            [54,3800,3600,  1, 'cash',  $today->copy()->subDays(3)->setHour(15)],
            [51,2800,2800,  1, 'mpesa', $today->copy()->subDays(3)->setHour(16)],
        ], 500, -150); // -150 discrepancy

        // ── Shift 4: Aisha, yesterday ────────────────────────────────────────
        $shiftId4 = $this->insertShift(3, $today->copy()->subDays(1)->setHour(9)->setMinute(30), $today->copy()->subDays(1)->setHour(17), [
            [57, 850, 850,  1, 'cash',   $today->copy()->subDays(1)->setHour(10)],
            [60, 150, 150,  3, 'mpesa',  $today->copy()->subDays(1)->setHour(11)],
            [52,2600,2600,  1, 'cash',   $today->copy()->subDays(1)->setHour(13)],
            [50,2400,2400,  1, 'credit', $today->copy()->subDays(1)->setHour(15)],
        ], 500, 0);

        // ── Open shift today — James (staff) — seeds a live positive dashboard ──
        // Yesterday total = 6,300. Today = 7,850 → owner sees ↑ KES 1,550 more
        $openShiftId = DB::table('shifts')->insertGetId([
            'staff_id'         => 2,
            'opened_at'        => $today->copy()->setHour(9),
            'closed_at'        => null,
            'opening_float'    => 500,
            'cash_counted'     => 0,
            'mpesa_total'      => 0,
            'expected_cash'    => 500,
            'cash_discrepancy' => 0,
            'status'           => 'open',
            'created_at'       => $today->copy()->setHour(9),
            'updated_at'       => $today->copy()->setHour(9),
        ]);
        foreach ([
            // [product_id, unit_price, actual_price, qty, payment_type, time]
            // cash: 850 + 300 = 1,150  |  mpesa: 4,500 + 2,200 = 6,700  | total: 7,850
            [57,  850,  850, 1, 'cash',  $today->copy()->setHour(9)->setMinute(32)],
            [60,  150,  150, 2, 'cash',  $today->copy()->setHour(10)->setMinute(15)],
            [55, 4500, 4500, 1, 'mpesa', $today->copy()->setHour(11)->setMinute(0)],
            [53, 2200, 2200, 1, 'mpesa', $today->copy()->setHour(11)->setMinute(48)],
        ] as [$pid, $unit, $actual, $qty, $type, $at]) {
            DB::table('sales')->insert([
                'shift_id' => $openShiftId, 'staff_id' => 2,
                'product_id' => $pid, 'variant_id' => null, 'bottle_id' => null,
                'customer_id' => null, 'quantity_or_ml' => $qty,
                'unit_price' => $unit, 'actual_price' => $actual,
                'total' => $actual * $qty, 'payment_type' => $type,
                'voided_at' => null, 'created_at' => $at, 'updated_at' => $at,
            ]);
        }

        // ── Customers & credit ────────────────────────────────────────────────
        $c1 = DB::table('customers')->insertGetId([
            'name' => 'Brenda Achieng', 'phone' => '0712345678',
            'total_outstanding' => 2400,
            'created_at' => $today->copy()->subDays(1), 'updated_at' => $today->copy()->subDays(1),
        ]);
        $c2 = DB::table('customers')->insertGetId([
            'name' => 'Susan Wanjiku', 'phone' => '0723456789',
            'total_outstanding' => 850,
            'created_at' => $today->copy()->subDays(3), 'updated_at' => $today->copy()->subDays(3),
        ]);

        $creditSale = DB::table('sales')->where('payment_type','credit')->where('product_id',50)->first();
        if ($creditSale) {
            DB::table('sales')->where('id',$creditSale->id)->update(['customer_id'=>$c1]);
            DB::table('credit_ledger')->insert([
                'customer_id'=>$c1,'sale_id'=>$creditSale->id,'amount'=>2400,'paid'=>0,'balance'=>2400,
                'last_payment_at'=>null,'status'=>'open',
                'created_at'=>$today->copy()->subDays(1),'updated_at'=>$today->copy()->subDays(1),
            ]);
        }
        DB::table('credit_ledger')->insert([
            'customer_id'=>$c2,'sale_id'=>null,'amount'=>850,'paid'=>0,'balance'=>850,
            'last_payment_at'=>null,'status'=>'open',
            'created_at'=>$today->copy()->subDays(3),'updated_at'=>$today->copy()->subDays(3),
        ]);

        // ── Supplier balance ──────────────────────────────────────────────────
        DB::table('supplier_balances')->insert([
            'supplier_id'=>2,'restock_id'=>null,'total_cost'=>18500,'amount_paid'=>10000,'balance'=>8500,
            'settled_at'=>null,'created_at'=>$today->copy()->subDays(5),'updated_at'=>$today->copy()->subDays(5),
        ]);

        $this->info('Demo seeded.');
        $this->line('Shifts: ' . DB::table('shifts')->count() . ' (open: ' . DB::table('shifts')->where('status','open')->count() . ')');
        $this->line('Sales: ' . DB::table('sales')->count());
        $this->line('Customers: ' . DB::table('customers')->count() . ' | Credit owed: KES ' . DB::table('credit_ledger')->sum('balance'));
        $this->line('Supplier balance: KES ' . DB::table('supplier_balances')->sum('balance'));

        tenancy()->end();
        return 0;
    }

    private function insertShift(int $staffId, Carbon $open, Carbon $close, array $sales, float $float, float $discrepancy): int
    {
        $cashTotal  = 0;
        $mpesaTotal = 0;
        foreach ($sales as [$pid, $unit, $actual, $qty, $type, $at]) {
            $total = $actual * $qty;
            if ($type === 'cash')  $cashTotal  += $total;
            if ($type === 'mpesa') $mpesaTotal += $total;
        }
        $expected = $float + $cashTotal;
        $counted  = $expected + $discrepancy;

        $shiftId = DB::table('shifts')->insertGetId([
            'staff_id'         => $staffId,
            'opened_at'        => $open,
            'closed_at'        => $close,
            'opening_float'    => $float,
            'cash_counted'     => $counted,
            'mpesa_total'      => $mpesaTotal,
            'expected_cash'    => $expected,
            'cash_discrepancy' => $discrepancy,
            'status'           => 'closed',
            'created_at'       => $open,
            'updated_at'       => $close,
        ]);

        foreach ($sales as [$pid, $unit, $actual, $qty, $type, $at]) {
            DB::table('sales')->insert([
                'shift_id'       => $shiftId,
                'staff_id'       => $staffId,
                'product_id'     => $pid,
                'variant_id'     => null,
                'bottle_id'      => null,
                'customer_id'    => null,
                'quantity_or_ml' => $qty,
                'unit_price'     => $unit,
                'actual_price'   => $actual,
                'total'          => $actual * $qty,
                'payment_type'   => $type,
                'voided_at'      => null,
                'created_at'     => $at,
                'updated_at'     => $at,
            ]);
        }
        return $shiftId;
    }
}
