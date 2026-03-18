<?php

namespace App\Http\Controllers;

use App\Models\CreditLedger;
use App\Models\Customer;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        // Customers with open credit, ordered by oldest credit first (most urgent)
        $customers = Customer::whereHas('openCredit')
            ->with(['openCredit' => fn($q) => $q->with('sale.product')->orderBy('created_at')])
            ->get()
            ->sortByDesc(fn($c) => $c->openCredit->min('created_at'))
            ->values();

        $totalOwed = CreditLedger::where('status', 'open')->sum('balance');

        return view('credit.index', compact('customers', 'totalOwed'));
    }

    public function recordPayment(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $payment   = (float) $request->input('amount');
        $remaining = $payment;

        // Apply payment to oldest open entries first
        $entries = $customer->openCredit()->orderBy('created_at')->get();

        foreach ($entries as $entry) {
            if ($remaining <= 0) break;

            $toApply = min($remaining, (float) $entry->balance);
            $newPaid    = (float) $entry->paid + $toApply;
            $newBalance = round((float) $entry->balance - $toApply, 2);

            $entry->update([
                'paid'            => $newPaid,
                'balance'         => $newBalance,
                'status'          => $newBalance <= 0 ? 'settled' : 'open',
                'last_payment_at' => now(),
            ]);

            $remaining = round($remaining - $toApply, 2);
        }

        // Sync customer outstanding (recalculate from ledger)
        $outstanding = CreditLedger::where('customer_id', $customer->id)
            ->where('status', 'open')
            ->sum('balance');

        $customer->update(['total_outstanding' => $outstanding]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'outstanding' => (float) $outstanding]);
        }

        return redirect()->route('credit.index')
            ->with('success', 'Payment of Ksh ' . number_format($payment, 0) . ' recorded for ' . $customer->name);
    }
}
