<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class CloseStaleShifts extends Command
{
    protected $signature = 'shifts:close-stale';
    protected $description = 'Auto-close shifts open for more than 24 hours';

    public function handle()
    {
        $tenants = Tenant::all();
        $totalClosed = 0;

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            
            $staleShifts = \App\Models\Shift::where('status', 'open')
                ->where('opened_at', '<', now()->subHours(24))
                ->get();

            foreach ($staleShifts as $shift) {
                $shift->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                    'cash_counted' => $shift->sales()->where('payment_method', 'cash')->sum('amount_paid'),
                    'expected_cash' => $shift->sales()->where('payment_method', 'cash')->sum('amount_paid'),
                    'cash_discrepancy' => 0,
                    'mpesa_total' => $shift->sales()->where('payment_method', 'mpesa')->sum('amount_paid'),
                ]);
                
                $totalClosed++;
            }
            
            tenancy()->end();
        }

        $this->info("Closed {$totalClosed} stale shifts across all tenants");
        return 0;
    }
}
