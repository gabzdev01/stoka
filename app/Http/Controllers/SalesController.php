<?php

namespace App\Http\Controllers;

class SalesController extends Controller
{
    public function index()
    {
        if (!session('shift_id')) {
            $hour     = now()->hour;
            $greeting = match (true) {
                $hour < 12 => 'Good morning',
                $hour < 17 => 'Good afternoon',
                default    => 'Good evening',
            };

            return view('sales.open-shift', [
                'greeting' => $greeting,
            ]);
        }

        return view('sales.index');
    }

    public function activeShift()
    {
        return view('sales.shift');
    }

    public function history()
    {
        return view('sales.history');
    }
}
