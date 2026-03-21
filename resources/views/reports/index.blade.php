@extends('layouts.app')

@section('title', 'Reports')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Reports</h1>
        <p class="page-subtitle">Sales, stock, staff and deposits at a glance</p>
    </div>
</div>
@endsection

@section('styles')
<style>
/* ── Tab nav ───────────────────────────────────── */
.rep-tabs {
    display: flex;
    gap: 4px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 24px;
    width: fit-content;
    flex-wrap: wrap;
}
.rep-tab {
    height: 36px;
    padding: 0 18px;
    border: none;
    border-radius: 9px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
    background: transparent;
    cursor: pointer;
    text-decoration: none;
    display: flex; align-items: center;
    transition: color 0.12s, background 0.12s;
    white-space: nowrap;
}
.rep-tab:hover { color: var(--espresso); }
.rep-tab.active { background: #fff; color: var(--espresso); box-shadow: 0 1px 3px rgba(28,24,20,0.08); }

/* ── Period filter ─────────────────────────────── */
.rep-period {
    display: flex;
    gap: 8px;
    margin-bottom: 22px;
    flex-wrap: wrap;
    align-items: center;
}
.rep-period-btn {
    height: 32px; padding: 0 14px;
    border: 1.5px solid var(--border); border-radius: 20px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 12.5px; font-weight: 500; color: var(--muted);
    background: var(--surface); cursor: pointer;
    text-decoration: none; display: flex; align-items: center;
    transition: all 0.12s; white-space: nowrap;
}
.rep-period-btn:hover { border-color: var(--espresso); color: var(--espresso); }
.rep-period-btn.active { background: var(--espresso); border-color: var(--espresso); color: #fff; }
.rep-period-label {
    font-size: 12px; color: var(--muted);
    font-family: "Plus Jakarta Sans", sans-serif;
    margin-left: 4px;
}

/* ── Stat cards row ────────────────────────────── */
.rep-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
}
.rep-stat {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px 18px;
}
.rep-stat-label {
    font-size: 11px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.07em;
    margin-bottom: 6px; display: block;
}
.rep-stat-value {
    font-family: "DM Mono", monospace;
    font-size: 22px; font-weight: 500;
    color: var(--espresso);
}
.rep-stat-sub {
    font-size: 12px; color: var(--muted); margin-top: 3px;
}

/* ── Section card ──────────────────────────────── */
.rep-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
}
.rep-card-head {
    padding: 16px 20px 14px;
    border-bottom: 1px solid var(--border);
    font-size: 11px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.08em;
}
.rep-table { width: 100%; border-collapse: collapse; }
.rep-table th {
    font-size: 11px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.06em;
    padding: 10px 16px; text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.rep-table td {
    padding: 11px 16px;
    border-bottom: 1px solid #F5F0EB;
    font-size: 13.5px; color: var(--espresso);
    vertical-align: middle;
}
.rep-table tbody tr:last-child td { border-bottom: none; }
.rep-table .mono { font-family: "DM Mono", monospace; }
.rep-table .muted { color: var(--muted); }
.rep-empty {
    padding: 32px 20px;
    text-align: center;
    color: var(--muted);
    font-size: 13.5px;
}

/* ── Stock badge ───────────────────────────────── */
.stock-badge {
    display: inline-flex; align-items: center;
    height: 22px; padding: 0 9px;
    border-radius: 11px;
    font-family: "DM Mono", monospace;
    font-size: 12px; font-weight: 500;
}
.stock-badge.out  { background: #FDEAE3; color: var(--clay); }
.stock-badge.low  { background: #FEF4E8; color: var(--terracotta); }
.stock-badge.ok   { background: #EFF5EF; color: var(--forest); }

/* ── Bar chart (pure CSS) ──────────────────────── */
.rep-bars { padding: 16px 20px; }
.rep-bar-row {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 8px; font-size: 12px;
}
.rep-bar-label { min-width: 55px; color: var(--muted); font-family: "Plus Jakarta Sans", sans-serif; }
.rep-bar-track {
    flex: 1; height: 10px;
    background: var(--surface);
    border-radius: 5px;
    overflow: hidden;
}
.rep-bar-fill {
    height: 100%; border-radius: 5px;
    background: var(--terracotta);
    transition: width 0.4s ease;
}
.rep-bar-val {
    min-width: 80px; text-align: right;
    font-family: "DM Mono", monospace;
    font-size: 12px; color: var(--espresso);
}

/* ── Staff disc pill ───────────────────────────── */
.disc-pill {
    font-size: 11.5px; font-weight: 500;
    padding: 2px 8px; border-radius: 10px;
}
.disc-ok   { background: #EFF5EF; color: var(--forest); }
.disc-warn { background: #FDEAE3; color: var(--clay); }
</style>
@endsection

@section('content')

{{-- Tab nav --}}
<div class="rep-tabs">
    <a href="?tab=sales&period={{ $period }}"  class="rep-tab {{ $tab === 'sales'    ? 'active' : '' }}">Sales</a>
    <a href="?tab=staff&period={{ $period }}"  class="rep-tab {{ $tab === 'staff'    ? 'active' : '' }}">Staff</a>
    <a href="?tab=stock&period={{ $period }}"  class="rep-tab {{ $tab === 'stock'    ? 'active' : '' }}">Stock</a>
    <a href="?tab=deposits&period={{ $period }}" class="rep-tab {{ $tab === 'deposits' ? 'active' : '' }}">Deposits</a>
</div>

{{-- Period filter (not shown on stock tab) --}}
@if($tab !== 'stock')
<div class="rep-period">
    <a href="?tab={{ $tab }}&period=today" class="rep-period-btn {{ $period === 'today' ? 'active' : '' }}">Today</a>
    <a href="?tab={{ $tab }}&period=week"  class="rep-period-btn {{ $period === 'week'  ? 'active' : '' }}">This week</a>
    <a href="?tab={{ $tab }}&period=month" class="rep-period-btn {{ $period === 'month' ? 'active' : '' }}">This month</a>
    <span class="rep-period-label">{{ $from->format('d M') }} – {{ $to->format('d M Y') }}</span>
</div>
@endif


{{-- ═══════════════════ SALES TAB ═══════════════════ --}}
@if($tab === 'sales')

<div class="rep-stats">
    <div class="rep-stat">
        <span class="rep-stat-label">Revenue</span>
        <div class="rep-stat-value">{{ tenant('currency_symbol') }} {{ number_format((int)$data['total']) }}</div>
        <div class="rep-stat-sub">{{ $data['count'] }} sale{{ $data['count'] !== 1 ? 's' : '' }}</div>
    </div>
    <div class="rep-stat">
        <span class="rep-stat-label">Cash</span>
        <div class="rep-stat-value">{{ tenant('currency_symbol') }} {{ number_format((int)$data['cash']) }}</div>
    </div>
    <div class="rep-stat">
        <span class="rep-stat-label">M-Pesa</span>
        <div class="rep-stat-value">{{ tenant('currency_symbol') }} {{ number_format((int)$data['mpesa']) }}</div>
    </div>
    <div class="rep-stat">
        <span class="rep-stat-label">Deposits</span>
        <div class="rep-stat-value">{{ tenant('currency_symbol') }} {{ number_format((int)$data['deposit']) }}</div>
    </div>
</div>

@if($data['total'] > 0)
<div class="rep-card" style="margin-bottom:20px;">
    <div class="rep-card-head">Payment split</div>
    <div class="rep-bars">
        @php $tot = max(1, $data['total']); @endphp
        @foreach(['Cash' => $data['cash'], 'M-Pesa' => $data['mpesa'], 'Deposits' => $data['deposit']] as $label => $val)
        @if($val > 0)
        <div class="rep-bar-row">
            <span class="rep-bar-label">{{ $label }}</span>
            <div class="rep-bar-track">
                <div class="rep-bar-fill" style="width:{{ round($val / $tot * 100) }}%"></div>
            </div>
            <span class="rep-bar-val">{{ tenant('currency_symbol') }} {{ number_format((int)$val) }}</span>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endif

<div class="rep-card">
    <div class="rep-card-head">Top products by revenue</div>
    @if($data['top_products']->isEmpty())
    <div class="rep-empty">No sales in this period.</div>
    @else
    <table class="rep-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Sales</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['top_products'] as $i => $p)
            <tr>
                <td>
                    <span style="color:var(--muted);font-size:11px;margin-right:6px;">{{ $i + 1 }}</span>
                    {{ $p->name }}
                </td>
                <td class="muted">{{ $p->sale_count }}</td>
                <td class="mono">{{ tenant('currency_symbol') }} {{ number_format((int)$p->revenue) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@if($data['daily']->count() > 1)
<div class="rep-card">
    <div class="rep-card-head">Daily revenue</div>
    <div class="rep-bars">
        @php $maxDay = $data['daily']->max('total') ?: 1; @endphp
        @foreach($data['daily'] as $day)
        <div class="rep-bar-row">
            <span class="rep-bar-label">{{ \Carbon\Carbon::parse($day->day)->format('d M') }}</span>
            <div class="rep-bar-track">
                <div class="rep-bar-fill" style="width:{{ round($day->total / $maxDay * 100) }}%"></div>
            </div>
            <span class="rep-bar-val">{{ tenant('currency_symbol') }} {{ number_format((int)$day->total) }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif


{{-- ═══════════════════ STAFF TAB ═══════════════════ --}}
@elseif($tab === 'staff')

@if(empty($data['staff']))
<div class="rep-card"><div class="rep-empty">No closed shifts in this period.</div></div>
@else
<div class="rep-card">
    <div class="rep-card-head">Performance by staff</div>
    <table class="rep-table">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Shifts</th>
                <th>Total sales</th>
                <th>Avg / shift</th>
                <th>Best shift</th>
                <th>Discrepancy</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['staff'] as $s)
            @php
                $avg  = $s['shifts'] > 0 ? $s['total'] / $s['shifts'] : 0;
                $disc = $s['disc_total'];
            @endphp
            <tr>
                <td style="font-weight:600;">{{ $s['name'] }}</td>
                <td class="muted">{{ $s['shifts'] }}</td>
                <td class="mono">{{ tenant('currency_symbol') }} {{ number_format((int)$s['total']) }}</td>
                <td class="mono muted">{{ tenant('currency_symbol') }} {{ number_format((int)$avg) }}</td>
                <td class="mono">{{ tenant('currency_symbol') }} {{ number_format((int)$s['best_shift']) }}</td>
                <td>
                    @if(round($disc) === 0)
                    <span class="disc-pill disc-ok">Balanced</span>
                    @else
                    <span class="disc-pill disc-warn">{{ $disc < 0 ? '−' : '+' }}{{ tenant('currency_symbol') }} {{ number_format(abs($disc), 0) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="rep-card">
    <div class="rep-card-head">All shifts in period</div>
    @if($data['shifts']->isEmpty())
    <div class="rep-empty">No closed shifts in this period.</div>
    @else
    <table class="rep-table">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Date</th>
                <th>Total</th>
                <th>Cash / M-Pesa</th>
                <th>Discrepancy</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['shifts']->sortByDesc('opened_at') as $sh)
            @php
                $shSales  = \App\Models\Sale::where('shift_id', $sh->id)->whereNull('voided_at');
                $shTotal  = (int) $shSales->sum('total');
                $shCash   = (int) $shSales->where('payment_type', 'cash')->sum('total');
                $shMpesa  = (int) $shSales->where('payment_type', 'mpesa')->sum('total');
                $shDisc   = (float) $sh->cash_discrepancy;
            @endphp
            <tr>
                <td>{{ $sh->staff->name ?? '—' }}</td>
                <td class="muted">{{ $sh->opened_at->format('d M') }}</td>
                <td class="mono">{{ tenant('currency_symbol') }} {{ number_format($shTotal) }}</td>
                <td class="muted" style="font-size:12px;">
                    {{ tenant('currency_symbol') }} {{ number_format($shCash) }} cash<br>
                    {{ tenant('currency_symbol') }} {{ number_format($shMpesa) }} M-Pesa
                </td>
                <td>
                    @if(round($shDisc) === 0)
                    <span style="color:var(--forest);font-size:12px;">Balanced</span>
                    @else
                    <span style="color:{{ $shDisc < 0 ? 'var(--clay)' : 'var(--terracotta)' }};font-size:12px;">
                        {{ $shDisc < 0 ? 'Short' : 'Over' }} {{ tenant('currency_symbol') }} {{ number_format(abs($shDisc), 0) }}
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>


{{-- ═══════════════════ STOCK TAB ═══════════════════ --}}
@elseif($tab === 'stock')

<div class="rep-stats" style="margin-bottom:20px;">
    <div class="rep-stat">
        <span class="rep-stat-label">Total products</span>
        <div class="rep-stat-value">{{ $data['products']->count() }}</div>
    </div>
    <div class="rep-stat">
        <span class="rep-stat-label">Out of stock</span>
        <div class="rep-stat-value" style="{{ $data['out_count'] > 0 ? 'color:var(--clay)' : '' }}">{{ $data['out_count'] }}</div>
    </div>
    <div class="rep-stat">
        <span class="rep-stat-label">Running low</span>
        <div class="rep-stat-value" style="{{ $data['low_count'] > 0 ? 'color:var(--terracotta)' : '' }}">{{ $data['low_count'] }}</div>
    </div>
</div>

<div class="rep-card">
    <div class="rep-card-head">Current stock levels</div>
    <table class="rep-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['products'] as $p)
                @if($p->type === 'variant')
                <tr>
                    <td colspan="3" style="font-weight:600;font-size:13px;background:var(--surface);color:var(--muted);padding:8px 16px !important;font-size:11px;text-transform:uppercase;letter-spacing:0.06em;">
                        {{ $p->name }}
                        @if($p->category) <span style="font-weight:400;"> · {{ $p->category }}</span> @endif
                    </td>
                </tr>
                @foreach($p->variants as $v)
                @php
                    $vs = (int)$v->stock;
                    $vc = $vs === 0 ? 'out' : ($vs <= (int)$p->low_stock_threshold ? 'low' : 'ok');
                @endphp
                <tr>
                    <td style="padding-left:28px;color:var(--muted);">{{ $v->size }}{{ $v->colour ? ' · '.$v->colour : '' }}</td>
                    <td></td>
                    <td><span class="stock-badge {{ $vc }}">{{ $vs === 0 ? 'Out' : $vs }}</span></td>
                </tr>
                @endforeach
                @elseif($p->type === 'measured')
                @php
                    $b   = $p->bottles->first();
                    $pct = $b ? ((float)$b->remaining_ml / max(1,(float)$b->total_ml)) : 0;
                    $mc  = (!$b || $pct==0) ? 'out' : ($pct < 0.20 ? 'low' : 'ok');
                @endphp
                <tr>
                    <td>{{ $p->name }}</td>
                    <td class="muted">{{ $p->category }}</td>
                    <td><span class="stock-badge {{ $mc }}">{{ !$b ? 'Out' : number_format($b->remaining_ml,0).'ml' }}</span></td>
                </tr>
                @else
                @php
                    $us = (int)$p->stock;
                    $uc = $us === 0 ? 'out' : ($us <= (int)$p->low_stock_threshold ? 'low' : 'ok');
                @endphp
                <tr>
                    <td>{{ $p->name }}</td>
                    <td class="muted">{{ $p->category }}</td>
                    <td><span class="stock-badge {{ $uc }}">{{ $us === 0 ? 'Out' : $us }}</span></td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

@if($data['restocks']->isNotEmpty())
<div class="rep-card">
    <div class="rep-card-head">Recent restocks</div>
    <table class="rep-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Supplier</th>
                <th>Items</th>
                <th>Invoice total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['restocks'] as $r)
            <tr>
                <td class="muted">{{ $r->created_at->format('d M Y') }}</td>
                <td>{{ $r->supplier->name ?? 'Direct' }}</td>
                <td class="muted">{{ $r->items->count() }} item{{ $r->items->count() !== 1 ? 's' : '' }}</td>
                <td class="mono">{{ $r->total_cost > 0 ? tenant('currency_symbol').' '.number_format((int)$r->total_cost) : '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif


{{-- ═══════════════════ DEPOSITS TAB ═══════════════════ --}}
@elseif($tab === 'deposits')

<div class="rep-stats">
    <div class="rep-stat">
        <span class="rep-stat-label">Outstanding</span>
        <div class="rep-stat-value" style="{{ $data['total_open'] > 0 ? 'color:var(--clay)' : '' }}">
            {{ tenant('currency_symbol') }} {{ number_format((int)$data['total_open']) }}
        </div>
        <div class="rep-stat-sub">{{ $data['open_customers']->count() }} customer{{ $data['open_customers']->count() !== 1 ? 's' : '' }}</div>
    </div>
    <div class="rep-stat">
        <span class="rep-stat-label">Collected this period</span>
        <div class="rep-stat-value" style="color:var(--forest)">
            {{ tenant('currency_symbol') }} {{ number_format((int)$data['total_settled']) }}
        </div>
    </div>
</div>

@if($data['open_customers']->isEmpty())
<div class="rep-card"><div class="rep-empty">No outstanding deposits. All settled!</div></div>
@else
<div class="rep-card">
    <div class="rep-card-head">Outstanding deposit balances</div>
    <table class="rep-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Phone</th>
                <th>Balance owed</th>
                <th>Days outstanding</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['open_customers'] as $c)
            @php
                $bal     = $c->openCredit->sum('balance');
                $oldest  = $c->openCredit->min('created_at');
                $ageDays = $oldest ? now()->diffInDays($oldest) : 0;
            @endphp
            <tr>
                <td style="font-weight:600;">{{ $c->name }}</td>
                <td class="muted">{{ $c->phone ?? '—' }}</td>
                <td class="mono" style="color:var(--clay)">{{ tenant('currency_symbol') }} {{ number_format((int)$bal) }}</td>
                <td>
                    <span style="color:{{ $ageDays >= 30 ? 'var(--clay)' : ($ageDays >= 14 ? 'var(--terracotta)' : 'var(--muted)') }}">
                        {{ $ageDays }} day{{ $ageDays !== 1 ? 's' : '' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($data['recent_payments']->isNotEmpty())
<div class="rep-card">
    <div class="rep-card-head">Recent payments received</div>
    <table class="rep-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Amount paid</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['recent_payments'] as $e)
            <tr>
                <td>{{ $e->customer->name ?? '—' }}</td>
                <td class="mono" style="color:var(--forest)">{{ tenant('currency_symbol') }} {{ number_format((int)$e->paid) }}</td>
                <td class="muted">{{ $e->last_payment_at?->format('d M Y') ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endif

@endsection
