<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
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
        return view('shifts.close');
    }
}
