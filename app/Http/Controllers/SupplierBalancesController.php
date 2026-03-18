<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierBalance;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class SupplierBalancesController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::whereHas('balances', fn($q) => $q->where('balance', '>', 0))
            ->with([
                'balances' => fn($q) => $q->where('balance', '>', 0)->with('restock')->orderBy('created_at'),
            ])
            ->orderBy('name')
            ->get();

        $totalOwed = SupplierBalance::where('balance', '>', 0)->sum('balance');

        return view('supplier-balances.index', compact('suppliers', 'totalOwed'));
    }

    public function recordPayment(Request $request, Supplier $supplier)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        $amount = (float) $request->amount;
        $remaining = $amount;

        $entries = SupplierBalance::where('supplier_id', $supplier->id)
            ->where('balance', '>', 0)
            ->orderBy('created_at')
            ->get();

        foreach ($entries as $entry) {
            if ($remaining <= 0) break;
            $apply = min($remaining, (float) $entry->balance);
            $entry->amount_paid += $apply;
            $entry->balance     -= $apply;
            $entry->settled_at   = $entry->balance <= 0 ? now() : null;
            $entry->save();
            $remaining -= $apply;
        }

        SupplierPayment::create([
            'supplier_id' => $supplier->id,
            'amount'      => $amount - max(0, $remaining), // actual applied
            'recorded_by' => session('auth_user'),
        ]);

        $outstanding = SupplierBalance::where('supplier_id', $supplier->id)
            ->where('balance', '>', 0)
            ->sum('balance');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'outstanding' => (float) $outstanding]);
        }

        return back()->with('success', 'Payment of Ksh ' . number_format($amount) . ' recorded.');
    }
}
