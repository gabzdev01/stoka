<?php

namespace App\Http\Controllers;

use App\Models\CreditLedger;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'sales');

        // ── Date range helpers ──────────────────────────────────────
        $period = $request->input('period', 'month');
        [$from, $to] = match ($period) {
            'today' => [now()->startOfDay(),  now()->endOfDay()],
            'week'  => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };

        $data = [];

        // ── Sales Summary ───────────────────────────────────────────
        if ($tab === 'sales') {
            $sales = Sale::whereNull('voided_at')
                ->whereBetween("sales.created_at", [$from, $to])
                ->get();

            $data['total']      = $sales->sum('total');
            $data['count']      = $sales->count();
            $data['cash']       = $sales->where('payment_type', 'cash')->sum('total');
            $data['mpesa']      = $sales->where('payment_type', 'mpesa')->sum('total');
            $data['deposit']    = $sales->where('payment_type', 'credit')->sum('total');

            // Top products by revenue
            $data['top_products'] = Sale::whereNull('voided_at')
                ->whereBetween("sales.created_at", [$from, $to])
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->select('products.name', DB::raw('SUM(sales.total) as revenue'), DB::raw('SUM(sales.quantity_or_ml) as qty_sold'), DB::raw('COUNT(*) as sale_count'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get();

            // Daily totals for simple bar (last 7 days if week/month)
            $data['daily'] = Sale::whereNull('voided_at')
                ->whereBetween("sales.created_at", [$from, $to])
                ->select(DB::raw('DATE(sales.created_at) as day'), DB::raw('SUM(total) as total'))
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        }

        // ── Staff Performance ───────────────────────────────────────
        if ($tab === 'staff') {
            $shifts = Shift::with('staff')
                ->where('status', 'closed')
                ->whereBetween('opened_at', [$from, $to])
                ->get();

            // Group by staff
            $staffData = [];
            foreach ($shifts as $shift) {
                $sid   = $shift->staff_id;
                $name  = $shift->staff->name ?? 'Unknown';
                $total = Sale::where('shift_id', $shift->id)->whereNull('voided_at')->sum('total');
                $count = Sale::where('shift_id', $shift->id)->whereNull('voided_at')->count();

                if (!isset($staffData[$sid])) {
                    $staffData[$sid] = [
                        'name'       => $name,
                        'shifts'     => 0,
                        'total'      => 0,
                        'sales'      => 0,
                        'best_shift' => 0,
                        'disc_total' => 0,
                    ];
                }
                $staffData[$sid]['shifts']++;
                $staffData[$sid]['total']      += (float) $total;
                $staffData[$sid]['sales']      += $count;
                $staffData[$sid]['disc_total'] += (float) $shift->cash_discrepancy;
                if ((float) $total > $staffData[$sid]['best_shift']) {
                    $staffData[$sid]['best_shift'] = (float) $total;
                }
            }

            // Sort by total desc
            usort($staffData, fn($a, $b) => $b['total'] <=> $a['total']);
            $data['staff'] = $staffData;
            $data['shifts'] = $shifts;
        }

        // ── Stock ───────────────────────────────────────────────────
        if ($tab === 'stock') {
            $products = Product::where('status', 'active')
                ->with(['variants', 'bottles' => fn($q) => $q->where('active', true)->orderByDesc('id')->limit(1)])
                ->orderBy('category')
                ->orderBy('name')
                ->get();

            // Recent restocks
            $restocks = \App\Models\Restock::with(['supplier', 'items.product'])
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            $data['products'] = $products;
            $data['restocks'] = $restocks;

            // Out of stock count
            $data['out_count'] = 0;
            $data['low_count'] = 0;
            foreach ($products as $p) {
                if ($p->type === 'variant') {
                    foreach ($p->variants as $v) {
                        if ((int)$v->stock === 0) $data['out_count']++;
                        elseif ((int)$v->stock <= (int)$p->low_stock_threshold) $data['low_count']++;
                    }
                } elseif ($p->type === 'measured') {
                    $b = $p->bottles->first();
                    $pct = $b ? ((float)$b->remaining_ml / max(1, (float)$b->total_ml)) : 0;
                    if (!$b || $pct == 0) $data['out_count']++;
                    elseif ($pct < 0.20) $data['low_count']++;
                } else {
                    if ((int)$p->stock === 0) $data['out_count']++;
                    elseif ((int)$p->stock <= (int)$p->low_stock_threshold) $data['low_count']++;
                }
            }
        }

        // ── Deposits ────────────────────────────────────────────────
        if ($tab === 'deposits') {
            $customers = Customer::whereHas('credits')
                ->with(['credits' => fn($q) => $q->with('sale.product')->orderByDesc('created_at')])
                ->get();

            $data['open_customers']  = $customers->filter(fn($c) => $c->openCredit->isNotEmpty())->values();
            $data['total_open']      = CreditLedger::where('status', 'open')->sum('balance');
            $data['total_settled']   = CreditLedger::where('status', 'settled')
                ->whereBetween("credit_ledger.created_at", [$from, $to])->sum('paid');
            $data['recent_payments'] = CreditLedger::where('status', 'settled')
                ->orWhere(fn($q) => $q->where('paid', '>', 0))
                ->with('customer')
                ->orderByDesc('last_payment_at')
                ->limit(15)
                ->get()
                ->filter(fn($e) => $e->last_payment_at !== null);
        }

        return view('reports.index', compact('tab', 'period', 'from', 'to', 'data'));
    }
}
