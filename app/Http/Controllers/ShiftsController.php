<?php

namespace App\Http\Controllers;

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
            ->with('activeSales')
            ->first();

        if (!$shift) {
            session()->forget('shift_id');
            return redirect()->route('sales.index');
        }

        $cashCounted  = round((float) $request->input('cash_counted'), 2);
        $sales        = $shift->activeSales;
        $cashSales    = (float) $sales->where('payment_type', 'cash')->sum('total');
        $mpesaSales   = (float) $sales->where('payment_type', 'mpesa')->sum('total');
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

        return redirect()->route('sales.history')->with('shift_closed', $msg);
    }
}
