<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shift;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    public function index()
    {
        $shifts = Shift::with('staff')
            ->orderByDesc('opened_at')
            ->get();

        // Attach active sale counts for open shifts
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

        return view('shifts.index', compact('shifts', 'openCount', 'closedCount'));
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

        $expectedCash    = $shift->expected_cash !== null
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
        $request->validate([
            'opening_float' => ['required', 'numeric', 'min:0'],
        ]);

        // Prevent opening a second shift if one is already open
        if (session('shift_id')) {
            return redirect()->route('sales.index');
        }

        $shift = Shift::create([
            'staff_id'      => session('auth_user'),
            'opened_at'     => now(),
            'opening_float' => $request->input('opening_float', 0),
            'mpesa_total'   => 0,
            'status'        => 'open',
        ]);

        session(['shift_id' => $shift->id]);

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
                $sales       = $shift->activeSales;
                $cashSales   = (float) $sales->where('payment_type', 'cash')->sum('total');
                $mpesaSales  = (float) $sales->where('payment_type', 'mpesa')->sum('total');
                $creditSales = (float) $sales->where('payment_type', 'credit')->sum('total');
                $totalSales  = (float) $sales->sum('total');
                $saleCount   = $sales->count();
                $expectedCash = round((float) $shift->opening_float + $cashSales, 2);
            }
        }

        return view('sales.close', compact(
            'shift',
            'totalSales', 'cashSales', 'mpesaSales', 'creditSales',
            'saleCount', 'expectedCash'
        ));
    }

    public function close(Request $request)
    {
        $request->validate([
            'cash_counted' => ['required', 'numeric', 'min:0'],
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

        $cashCounted  = round((float) $request->input('cash_counted'), 2);
        $sales        = $shift->activeSales;
        $cashSales    = (float) $sales->where('payment_type', 'cash')->sum('total');
        $mpesaSales   = (float) $sales->where('payment_type', 'mpesa')->sum('total');
        $creditSales  = (float) $sales->where('payment_type', 'credit')->sum('total');
        $totalSales   = (float) $sales->sum('total');
        $saleCount    = $sales->count();
        $expectedCash = round((float) $shift->opening_float + $cashSales, 2);
        $discrepancy  = round($cashCounted - $expectedCash, 2);

        $shift->update([
            'cash_counted'     => $cashCounted,
            'expected_cash'    => $expectedCash,
            'cash_discrepancy' => $discrepancy,
            'mpesa_total'      => $mpesaSales,
            'closed_at'        => now(),
            'status'           => 'closed',
        ]);

        session()->forget('shift_id');

        if ($discrepancy == 0) {
            $msg = 'Shift closed · Balanced ✓';
        } elseif ($discrepancy < 0) {
            $msg = 'Shift closed · Ksh ' . number_format(abs($discrepancy), 0) . ' short';
        } else {
            $msg = 'Shift closed · Ksh ' . number_format($discrepancy, 0) . ' over';
        }

        // ── Trigger 1: shift close report to owner ──────────────────────
        try {
            $ownerWa = tenant('owner_whatsapp');
            if ($ownerWa) {
                $wa      = new WhatsAppService();
                $waMsg   = self::buildShiftCloseMessage(
                    $shift, $cashSales, $mpesaSales, $creditSales,
                    $totalSales, $saleCount, $cashCounted, $expectedCash, $discrepancy, $sales
                );
                $sent = $wa->send($ownerWa, $waMsg);
                if ($sent) {
                    $shift->update(['wa_sent_at' => now()]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Shift close WA error', ['error' => $e->getMessage()]);
        }

        return redirect()->route('sales.history')->with('shift_closed', $msg);
    }

    private static function buildShiftCloseMessage(
        Shift $shift,
        float $cashSales,
        float $mpesaSales,
        float $creditSales,
        float $totalSales,
        int   $saleCount,
        float $cashCounted,
        float $expectedCash,
        float $discrepancy,
        $sales
    ): string {
        $hour     = now()->hour;
        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default    => 'Good evening',
        };

        $ownerName  = tenant('owner_name') ?? '';
        $ownerFirst = explode(' ', trim($ownerName))[0];
        $staffFirst = explode(' ', trim($shift->staff->name ?? 'Staff'))[0];

        $openedAt  = $shift->opened_at;
        $closedAt  = $shift->closed_at ?? now();
        $duration  = $openedAt->diff($closedAt);
        $durStr    = $duration->h > 0
            ? $duration->h . 'h ' . $duration->i . 'm'
            : $duration->i . 'm';
        $dateStr   = $openedAt->format('D j M');
        $timeStr   = $openedAt->format('H:i') . ' – ' . $closedAt->format('H:i');

        $nf = fn(float $v) => 'Ksh ' . number_format($v, 0);

        $lines   = [];
        $lines[] = $greeting . ', ' . $ownerFirst . '!';
        $lines[] = '';
        $lines[] = $staffFirst . "'s shift is closed.";
        $lines[] = '';
        $lines[] = '📅 ' . $dateStr . ' · ' . $timeStr . ' (' . $durStr . ')';
        $lines[] = '🧾 ' . $saleCount . ' sale' . ($saleCount !== 1 ? 's' : '') . ' · ' . $nf($totalSales);
        $lines[] = '   Cash     ' . $nf($cashSales);
        $lines[] = '   M-Pesa   ' . $nf($mpesaSales);
        if ($creditSales > 0) {
            $lines[] = '   Credit   ' . $nf($creditSales);
        }
        $lines[] = '';
        $lines[] = '💵 Till count: ' . $nf($cashCounted);
        $lines[] = '   Expected:    ' . $nf($expectedCash);

        if ($discrepancy == 0) {
            $lines[] = '   ✅ Balanced';
        } elseif ($discrepancy < 0) {
            $lines[] = '   ⚠️ ' . $nf(abs($discrepancy)) . ' *short*';
        } else {
            $lines[] = '   ⚠️ ' . $nf($discrepancy) . ' *over*';
        }

        // Top seller (if any product sold 2+ times)
        $counts = [];
        foreach ($sales as $sale) {
            $pid = $sale->product_id;
            $counts[$pid] = ($counts[$pid] ?? 0) + 1;
        }
        arsort($counts);
        $topPid = array_key_first($counts);
        if ($topPid && $counts[$topPid] >= 2) {
            $topSale = $sales->firstWhere('product_id', $topPid);
            $topName = $topSale->product->name ?? 'Unknown';
            $lines[] = '';
            $lines[] = '🥇 Top item: ' . $topName . ' × ' . $counts[$topPid];
        }

        $lines[] = '';
        $lines[] = 'Powered by Stoka · stoka.co.ke';

        return implode("\n", $lines);
    }
}
