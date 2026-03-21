<?php

namespace App\Http\Controllers;

use App\Models\CreditLedger;
use App\Models\Product;
use App\Models\ProductBottle;
use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
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
        // Cap at 3: on demo, stale sessions can accumulate before the hygiene
        // gate runs. We show max 3 (1 per staff role) to protect the layout.
        $openShifts = Shift::where('status', 'open')
            ->with(['staff', 'activeSales'])
            ->orderByDesc('opened_at')
            ->get()
            ->unique('staff_id')   // dedupe: one card per person
            ->values();

        // Does the owner have their own open shift?
        $ownerHasOpenShift = $openShifts->contains('staff_id', session('auth_user'));

        // Zone 3 — today's totals
        $todaySales = Sale::whereDate('created_at', $today)
            ->whereNull('voided_at')
            ->get();
        $todayTotal = $todaySales->sum('total');
        $todayCash  = $todaySales->where('payment_type', 'cash')->sum('total');
        $todayMpesa = $todaySales->where('payment_type', 'mpesa')->sum('total');
        $todayCount = $todaySales->count();

        // Yesterday comparison
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

        // Below-floor sales today
        $belowFloorSales = DB::table('sales')
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->join('users', 'users.id', '=', 'sales.staff_id')
            ->leftJoin('product_variants', 'product_variants.id', '=', 'sales.variant_id')
            ->whereNull('sales.voided_at')
            ->whereNotNull('products.floor_price')
            ->whereRaw('sales.actual_price < products.floor_price')
            ->whereDate('sales.created_at', $today)
            ->select(
                'sales.id',
                'sales.actual_price',
                'sales.quantity_or_ml',
                'sales.created_at',
                'products.name as product_name',
                'products.floor_price',
                'products.shelf_price',
                'product_variants.size as variant_size',
                'users.name as staff_name'
            )
            ->orderByDesc('sales.created_at')
            ->get();

        // Zone 5 — recent closed shifts
        $recentShifts = Shift::where('status', 'closed')
            ->with(['staff', 'activeSales'])
            ->orderBy('closed_at', 'desc')
            ->limit(5)
            ->get();

        // ── Insight sentence ──────────────────────────────────────
        $oosCount = collect()
            ->merge($outOfStock->pluck('id'))
            ->merge($outVariants->pluck('product_id'))
            ->unique()->count();
        $lowCount = collect()
            ->merge($lowStock->pluck('id'))
            ->merge($lowVariants->pluck('product_id'))
            ->merge($lowBottles->pluck('product_id'))
            ->unique()->diff(
                collect()->merge($outOfStock->pluck('id'))->merge($outVariants->pluck('product_id'))->unique()
            )->count();

        $insight = null;
        $insightPool = [];

        // Top-selling product this month
        $topProduct = DB::table('sales')
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->whereNull('sales.voided_at')
            ->where('sales.created_at', '>=', now()->startOfMonth())
            ->select('products.name', DB::raw('COUNT(*) as sale_count'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sale_count')
            ->first();

        if ($topProduct && $topProduct->sale_count >= 3) {
            $insightPool[] = "{$topProduct->name} has sold {$topProduct->sale_count} times this month.";
        }



        if ($todayTotal > 0 && $yesterdayTotal > 0 && $todayTotal > $yesterdayTotal) {
            $pct = round((($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100);
            if ($pct >= 10) {
                $insightPool[] = "Today is already {$pct}% stronger than yesterday.";
            }
        }

        if ($discrepancyShifts->isEmpty() && $recentShifts->count() >= 3) {
            $insightPool[] = 'All recent shifts closed balanced.';
        }

        if ($lowCount === 0 && $oosCount === 0 && $todayCount > 0) {
            $insightPool[] = 'Stock levels are looking good today.';
        }

        if (!empty($insightPool)) {
            // Pick deterministically by day of year so it changes daily, not on every refresh
            $insight = $insightPool[now()->dayOfYear % count($insightPool)];
        }

        // ── Nav alert flags (stored in session for sidebar) ───────
        $navAlerts = [];
        if ($creditOwed > 0 || $overdueCredit->isNotEmpty()) $navAlerts[] = 'credit';
        if ($oosCount > 0 || $lowCount > 0)                  $navAlerts[] = 'shopping-list';
        if ($supplierBalances->sum('total') > 0)              $navAlerts[] = 'supplier-pay';
        if ($discrepancyShifts->isNotEmpty() || $belowFloorSales->isNotEmpty()) $navAlerts[] = 'shifts';
        session(['nav_alerts' => $navAlerts]);

        // ── While you were away ───────────────────────────────────
        $awayData = null;
        $user = User::find(session('auth_user'));

        if ($user && $user->dashboard_last_seen) {
            $lastSeen = Carbon::parse($user->dashboard_last_seen);
            $daysSince = (int) $lastSeen->diffInDays(now());

            if ($daysSince >= 3) {
                $awaySales = Sale::where('created_at', '>=', $lastSeen)
                    ->whereNull('voided_at')
                    ->get();

                $bestShift = Shift::where('status', 'closed')
                    ->where('closed_at', '>=', $lastSeen)
                    ->with(['staff', 'activeSales'])
                    ->get()
                    ->sortByDesc(fn($s) => $s->activeSales->sum('total'))
                    ->first();

                $lowItem = $lowStock->first()
                    ?? ($lowBottles->isNotEmpty() ? $lowBottles->first()->product : null);

                $awayData = [
                    'since_label' => $lastSeen->format('l'),   // e.g. "Tuesday"
                    'days'        => $daysSince,
                    'count'       => $awaySales->count(),
                    'total'       => (int) $awaySales->sum('total'),
                    'best_shift'  => $bestShift,
                    'low_item'    => $lowItem,
                ];
            }
        }

        // Update last seen
        if ($user) {
            $user->dashboard_last_seen = now();
            $user->save();
        }

        // ── Monthly story (1st of month only) ─────────────────────
        $monthlyStory = null;
        if ($today->day === 1) {
            $lastMonth      = now()->subMonth();
            $monthStart     = $lastMonth->copy()->startOfMonth();
            $monthEnd       = $lastMonth->copy()->endOfMonth();
            $monthName      = $lastMonth->format('F');

            $monthSales = Sale::whereBetween('created_at', [$monthStart, $monthEnd])
                ->whereNull('voided_at')
                ->get();
            $monthTotal = (int) $monthSales->sum('total');
            $monthCount = $monthSales->count();

            $monthTopProduct = DB::table('sales')
                ->join('products', 'products.id', '=', 'sales.product_id')
                ->whereNull('sales.voided_at')
                ->whereBetween('sales.created_at', [$monthStart, $monthEnd])
                ->select('products.name', DB::raw('COUNT(*) as sale_count'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('sale_count')
                ->first();

            $monthShiftCount = Shift::where('status', 'closed')
                ->whereBetween('closed_at', [$monthStart, $monthEnd])
                ->count();

            // Best staff member last month
            $bestStaff = Shift::where('status', 'closed')
                ->whereBetween('closed_at', [$monthStart, $monthEnd])
                ->with(['staff', 'activeSales'])
                ->get()
                ->groupBy('staff_id')
                ->map(fn($shifts) => [
                    'name'  => $shifts->first()->staff->name,
                    'total' => $shifts->sum(fn($s) => $s->activeSales->sum('total')),
                    'shifts' => $shifts->count(),
                ])
                ->sortByDesc('total')
                ->first();

            $monthlyStory = [
                'month'        => $monthName,
                'total'        => $monthTotal,
                'count'        => $monthCount,
                'top_product'  => $monthTopProduct,
                'shift_count'  => $monthShiftCount,
                'credit_owed'  => (int) $creditOwed,
                'best_staff'   => $bestStaff,
            ];
        }

        $shopEnabled      = (bool) tenant()->shop_enabled;
        $shopVisibleCount = \App\Models\Product::where('shop_visible', true)->count();

        return view('dashboard', compact(
            'greeting', 'today', 'insight',
            'openShifts', 'ownerHasOpenShift',
            'todayTotal', 'todayCash', 'todayMpesa', 'todayCount', 'yesterdayTotal',
            'creditOwed',
            'outOfStock', 'lowStock',
            'lowBottles',
            'outVariants', 'lowVariants',
            'overdueCredit', 'recentCredit',
            'supplierBalances',
            'discrepancyShifts',
            'recentShifts',
            'oosCount', 'lowCount',
            'awayData',
            'monthlyStory',
            'belowFloorSales',
            'shopEnabled',
            'shopVisibleCount'
        ));
    }
}
