<?php

namespace App\Http\Controllers;

use App\Console\Commands\DemoReset;
use App\Models\CreditPayment;
use App\Models\Sale;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    public function index()
    {
        $shifts = Shift::with('staff')
            ->orderByDesc('opened_at')
            ->get();

        foreach ($shifts as $s) {
            if ($s->status === 'open') {
                $s->setAttribute('_sale_count',
                    Sale::where('shift_id', $s->id)->whereNull('voided_at')->count());
                $s->setAttribute('_sale_total',
                    Sale::where('shift_id', $s->id)->whereNull('voided_at')->sum('total'));
            }
        }

        $openCount   = $shifts->where('status', 'open')->count();
        $closedCount = $shifts->where('status', 'closed')->count();

        $staffCount = \App\Models\User::where('role', 'staff')->count();

        return view('shifts.index', compact('shifts', 'openCount', 'closedCount', 'staffCount'));
    }

    public function show(Shift $shift)
    {
        $shift->load([
            'staff',
            'sales' => fn($q) => $q->with(['product', 'variant'])->orderByDesc('created_at'),
        ]);

        $allSales    = $shift->sales;
        $active      = $allSales->whereNull('voided_at');
        $totalSales  = (float) $active->sum('total');
        $cashSales   = (float) $active->where('payment_type', 'cash')->sum('total');
        $mpesaSales  = (float) $shift->mpesa_total;
        $creditSales = (float) $active->where('payment_type', 'credit')->sum('total');
        $saleCount   = $active->count();

        $expectedCash = $shift->expected_cash !== null
            ? (float) $shift->expected_cash
            : round((float) $shift->opening_float + $cashSales, 2);

        $disc = (float) $shift->cash_discrepancy;

        return view('shifts.show', compact(
            'shift', 'allSales',
            'totalSales', 'cashSales', 'mpesaSales', 'creditSales',
            'saleCount', 'expectedCash', 'disc'
        ));
    }

    public function open(Request $request)
    {
        $isOwner = session('auth_role') === 'owner';
        $request->validate([
            'opening_float' => $isOwner ? ['nullable','numeric','min:0'] : ['required','numeric','min:0'],
        ]);

        if (session('shift_id')) {
            return redirect()->route('sales.index');
        }

        // Prevent duplicate shifts: reuse if this user already has one open in DB.
        // Critical for demo where multiple browser sessions share the same account.
        $existing = Shift::where('staff_id', session('auth_user'))
            ->where('status', 'open')
            ->latest('opened_at')
            ->first();

        if ($existing) {
            session(['shift_id' => $existing->id]);
            return redirect()->route('sales.index');
        }

        $shift = Shift::create([
            'staff_id'      => session('auth_user'),
            'opened_at'     => now(),
            'opening_float' => $request->input('opening_float', 0) ?? 0,
            'mpesa_total'   => 0,
            'status'        => 'open',
        ]);

        session(['shift_id' => $shift->id]);

        if (tenant('id') === 'demo') {
            DemoReset::restoreStock();
        }

        return redirect()->route('sales.index')
            ->with('success', 'Shift started. Good selling!');
    }

    public function closeForm()
    {
        $shiftId = session('shift_id');
        $shift   = null;

        $totalSales   = 0;
        $cashSales    = 0;
        $mpesaSales   = 0;
        $creditSales  = 0;
        $saleCount    = 0;
        $expectedCash = 0;

        if ($shiftId) {
            $shift = Shift::where('id', $shiftId)
                ->where('status', 'open')
                ->with(['staff', 'activeSales'])
                ->first();

            if ($shift) {
                $sales        = $shift->activeSales;
                $cashSales    = (float) $sales->where('payment_type', 'cash')->sum('total');
                $mpesaSales   = (float) $sales->where('payment_type', 'mpesa')->sum('total');
                $creditSales  = (float) $sales->where('payment_type', 'credit')->sum('total');
                $totalSales   = (float) $sales->sum('total');
                $saleCount    = $sales->count();
                $depositCash  = (float) CreditPayment::where('shift_id', $shiftId)->where('payment_type', 'cash')->sum('amount');
                $depositMpesa = (float) CreditPayment::where('shift_id', $shiftId)->where('payment_type', 'mpesa')->sum('amount');
                $expectedCash = round((float) $shift->opening_float + $cashSales + $depositCash, 2);
            }
        }

        // Owner with no float = no till to count — simplified close
        $isOwnerClose = $shift
            && session('auth_role') === 'owner'
            && (float) $shift->opening_float === 0.0;

        return view('sales.close', compact(
            'shift',
            'totalSales', 'cashSales', 'mpesaSales', 'creditSales',
            'saleCount', 'expectedCash',
            'depositCash', 'depositMpesa',
            'isOwnerClose'
        ));
    }

    public function close(Request $request)
    {
        $isOwner = session('auth_role') === 'owner';
        $request->validate([
            'cash_counted' => $isOwner ? ['nullable', 'numeric', 'min:0'] : ['required', 'numeric', 'min:0'],
        ]);

        $shiftId = session('shift_id');
        if (!$shiftId) {
            return redirect()->route('sales.index')
                ->with('error', 'No open shift to close.');
        }

        $shift = Shift::where('id', $shiftId)
            ->where('status', 'open')
            ->with(['activeSales', 'staff'])
            ->first();

        if (!$shift) {
            session()->forget('shift_id');
            return redirect()->route('sales.index');
        }

        $sales        = $shift->activeSales;
        $cashSales    = (float) $sales->where('payment_type', 'cash')->sum('total');
        $mpesaSales   = (float) $sales->where('payment_type', 'mpesa')->sum('total');
        $depositCash  = (float) CreditPayment::where('shift_id', $shiftId)->where('payment_type', 'cash')->sum('amount');
        $expectedCash = round((float) $shift->opening_float + $cashSales + $depositCash, 2);

        // Owner with no float: auto-balance, no counting step
        $isOwnerClose = session('auth_role') === 'owner' && (float) $shift->opening_float === 0.0;
        if ($isOwnerClose) {
            $cashCounted = $expectedCash;
            $discrepancy = 0;
        } else {
            $cashCounted = round((float) $request->input('cash_counted'), 2);
            $discrepancy = round($cashCounted - $expectedCash, 2);
        }

        $shift->update([
            'cash_counted'     => $cashCounted,
            'expected_cash'    => $expectedCash,
            'cash_discrepancy' => $discrepancy,
            'mpesa_total'      => $mpesaSales,
            'closed_at'        => now(),
            'status'           => 'closed',
        ]);

        session()->forget('shift_id');

        return redirect()->route('shifts.summary', $shift);
    }

    public function summary(Shift $shift)
    {
        $shift->load([
            'staff',
            'sales' => fn($q) => $q->with('product')->whereNull('voided_at'),
        ]);

        $sales        = $shift->sales;
        $cashSales    = (float) $sales->where('payment_type', 'cash')->sum('total');
        $mpesaSales   = (float) $sales->where('payment_type', 'mpesa')->sum('total');
        $creditSales  = (float) $sales->where('payment_type', 'credit')->sum('total');
        $totalSales   = (float) $sales->sum('total');
        $saleCount    = $sales->count();
        $discrepancy  = (float) $shift->cash_discrepancy;
        $cashCounted  = (float) $shift->cash_counted;
        $expectedCash = (float) $shift->expected_cash;

        $openedAt = $shift->opened_at;
        $closedAt = $shift->closed_at ?? now();
        $dur      = $openedAt->diff($closedAt);
        $durStr   = $dur->h > 0 ? $dur->h . 'h ' . $dur->i . 'm' : $dur->i . 'm';

        // Top seller — only if one product sold 2+ times
        $counts = [];
        foreach ($sales as $sale) {
            $pid = $sale->product_id;
            $counts[$pid] = ($counts[$pid] ?? 0) + 1;
        }
        arsort($counts);
        $topSeller = null;
        $topPid    = array_key_first($counts);
        if ($topPid && ($counts[$topPid] ?? 0) >= 2) {
            $topSale   = $sales->firstWhere('product_id', $topPid);
            $topSeller = [
                'name'  => $topSale->product->name ?? 'Unknown',
                'count' => $counts[$topPid],
            ];
        }

        $staffFirst = explode(' ', trim($shift->staff->name ?? 'Staff'))[0];
        $waMsg      = self::buildWaMessage(
            $staffFirst, $cashSales, $mpesaSales,
            $totalSales, $saleCount,
            $cashCounted, $expectedCash, $discrepancy,
            $topSeller
        );

        $waUrl   = null;
        $ownerWa = tenant('owner_whatsapp');
        if ($ownerWa) {
            $phone = self::formatWaPhone($ownerWa);
            $waUrl = 'https://wa.me/' . $phone . '?text=' . rawurlencode($waMsg);
        }

        return view('shifts.summary', [
            'shift'        => $shift,
            'openedAt'     => $openedAt->format('g:ia'),
            'closedAt'     => $closedAt->format('g:ia'),
            'duration'     => $durStr,
            'totalSales'   => $totalSales,
            'cashSales'    => $cashSales,
            'mpesaSales'   => $mpesaSales,
            'creditSales'  => $creditSales,
            'saleCount'    => $saleCount,
            'cashCounted'  => $cashCounted,
            'expectedCash' => $expectedCash,
            'discrepancy'  => $discrepancy,
            'waUrl'        => $waUrl,
            'waMsg'        => $waMsg,
        ]);
    }

    private static function buildWaMessage(
        string $staffFirst,
        float  $cashSales,
        float  $mpesaSales,
        float  $totalSales,
        int    $saleCount,
        float  $cashCounted,
        float  $expectedCash,
        float  $discrepancy,
        ?array $topSeller
    ): string {
        $hour     = now()->hour;
        $greeting = match (true) {
            $hour >= 6  && $hour < 12 => 'Good morning',
            $hour >= 12 && $hour < 17 => 'Good afternoon',
            $hour >= 17 && $hour < 22 => 'Good evening',
            default                   => 'Hi',
        };

        $ownerFirst = explode(' ', trim(tenant('owner_name') ?? ''))[0] ?: 'there';
        $shopName   = tenant('name') ?? 'the shop';

        $nf    = fn(float $v) => 'Ksh ' . number_format((int) round($v), 0);
        $sales = $saleCount . ' ' . ($saleCount === 1 ? 'sale' : 'sales');

        $lines   = [];
        $lines[] = $greeting . ' ' . $ownerFirst . ' 👋';
        $lines[] = '';
        $lines[] = $staffFirst . ' just closed their shift at ' . $shopName . '.';
        $lines[] = '';
        $lines[] = $sales . ' · ' . $nf($totalSales);
        $lines[] = 'Cash: ' . $nf($cashSales) . ' · M-Pesa: ' . $nf($mpesaSales);
        $lines[] = '';
        $lines[] = $staffFirst . ' counted ' . $nf($cashCounted) . ' in the till.';

        if ($discrepancy == 0) {
            $lines[] = 'Expected ' . $nf($expectedCash) . '. ✅ All balanced.';
            if ($topSeller) {
                $lines[] = '';
                $lines[] = 'Best seller today: ' . $topSeller['name'] . ' (' . $topSeller['count'] . ' sold)';
            }
        } elseif ($discrepancy < 0) {
            $lines[] = 'Expected ' . $nf($expectedCash) . '.';
            $lines[] = '';
            $lines[] = '⚠️ ' . $nf(abs($discrepancy)) . ' short.';
            $lines[] = '';
            $lines[] = 'This has been recorded in Stoka.';
        } else {
            $lines[] = 'Expected ' . $nf($expectedCash) . '. ✅ ' . $nf($discrepancy) . ' over.';
        }

        $lines[] = '';
        $lines[] = 'Powered by Stoka · stoka.co.ke';

        return implode("\n", $lines);
    }

    private static function formatWaPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-]/', '', $phone);
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '254')) {
            return $phone;
        }
        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            return '254' . substr($phone, 1);
        }
        if (strlen($phone) === 9) {
            return '254' . $phone;
        }

        return $phone;
    }
}
