<?php

namespace App\Http\Controllers;

use App\Models\CreditLedger;
use App\Models\Product;
use App\Models\ProductBottle;
use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $hour  = now()->hour;

        if ($hour >= 5 && $hour < 12)      $greeting = 'Good morning';
        elseif ($hour >= 12 && $hour < 17) $greeting = 'Good afternoon';
        elseif ($hour >= 17 && $hour < 21) $greeting = 'Good evening';
        else                               $greeting = 'Late night check?';

        // Zone 2 — open shifts (with eager-loaded sales)
        $openShifts = Shift::where('status', 'open')
            ->with(['staff', 'activeSales'])
            ->get();

        // Zone 3 — today's totals
        $todaySales = Sale::whereDate('created_at', $today)
            ->whereNull('voided_at')
            ->get();
        $todayTotal = $todaySales->sum('total');
        $todayCash  = $todaySales->where('payment_type', 'cash')->sum('total');
        $todayMpesa = $todaySales->where('payment_type', 'mpesa')->sum('total');
        $todayCount = $todaySales->count();

        // Yesterday comparison for Today's Sales card
        $yesterdayTotal = Sale::whereDate('created_at', today()->subDay())
            ->whereNull('voided_at')
            ->sum('total');

        // Credit outstanding
        $creditOwed = CreditLedger::where('status', 'open')->sum('balance');

        // Alerts — unit products OOS + low
        $outOfStock = Product::where('status', 'active')
            ->where('type', 'unit')
            ->where('stock', 0)
            ->get();
        $lowStock = Product::where('status', 'active')
            ->where('type', 'unit')
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('stock', '>', 0)
            ->get();

        // Alerts — measured bottles low
        $lowBottles = ProductBottle::where('active', true)
            ->whereRaw('remaining_ml / total_ml < 0.20')
            ->with('product')
            ->get();

        // Alerts — variant OOS + low
        $outVariants = ProductVariant::where('stock', 0)
            ->whereHas('product', fn($q) => $q->where('status', 'active')->where('type', 'variant'))
            ->with('product')
            ->get();
        $lowVariants = ProductVariant::where('stock', '>', 0)
            ->where('stock', '<=',
                DB::raw('(SELECT low_stock_threshold FROM products WHERE id = product_variants.product_id)'))
            ->whereHas('product', fn($q) => $q->where('status', 'active')->where('type', 'variant'))
            ->with('product')
            ->get();

        // Alerts — credit overdue (>30 days)
        $overdueCredit = CreditLedger::where('status', 'open')
            ->where('created_at', '<=', now()->subDays(30))
            ->with('customer')
            ->get();

        // Alerts — today's new credit tabs
        $recentCredit = CreditLedger::where('status', 'open')
            ->whereDate('created_at', $today)
            ->with('customer')
            ->get();

        // Alerts — supplier balances outstanding
        $supplierBalances = DB::table('supplier_balances')
            ->join('suppliers', 'suppliers.id', '=', 'supplier_balances.supplier_id')
            ->where('supplier_balances.balance', '>', 0)
            ->select(
                'suppliers.id',
                'suppliers.name',
                DB::raw('SUM(supplier_balances.balance) as total'),
                DB::raw('MIN(supplier_balances.created_at) as since')
            )
            ->groupBy('suppliers.id', 'suppliers.name')
            ->get();

        // Alerts — shift discrepancies (last 7 days)
        $discrepancyShifts = Shift::where('status', 'closed')
            ->whereNotNull('cash_discrepancy')
            ->where('cash_discrepancy', '!=', 0)
            ->where('closed_at', '>=', now()->subDays(7))
            ->with('staff')
            ->get();

        // Zone 5 — recent closed shifts
        $recentShifts = Shift::where('status', 'closed')
            ->with(['staff', 'activeSales'])
            ->orderBy('closed_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'greeting', 'today',
            'openShifts',
            'todayTotal', 'todayCash', 'todayMpesa', 'todayCount', 'yesterdayTotal',
            'creditOwed',
            'outOfStock', 'lowStock',
            'lowBottles',
            'outVariants', 'lowVariants',
            'overdueCredit', 'recentCredit',
            'supplierBalances',
            'discrepancyShifts',
            'recentShifts'
        ));
    }
}
