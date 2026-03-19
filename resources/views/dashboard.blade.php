@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
<div>
    <p class="dash-greeting">{{ $greeting }}, {{ session('auth_name') }}.</p>
    <p class="dash-date">{{ $today->isoFormat('dddd, D MMMM YYYY') }}</p>
</div>
@endsection

@section('styles')
<style>
/* ── Wrap ────────────────────────────────────────────── */
.dash-wrap {
    max-width: 1100px;
    margin: 0 auto;
}

/* ── Zone 1: Greeting ─────────────────────────────────── */
.dash-greeting {
    font-family: "Cormorant Garamond", serif;
    font-size: 28px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.1;
}
.dash-date {
    font-size: 13px;
    color: var(--muted);
    margin-top: 5px;
}

/* ── Yesterday comparison ─────────────────────────────── */
.stat-compare {
    font-size: 12px;
    margin-top: 5px;
    font-family: "Plus Jakarta Sans", sans-serif;
}
.stat-compare .cmp-num { font-family: "DM Mono", monospace; }
.stat-compare.cmp-up   { color: #4A6741; }
.stat-compare.cmp-down { color: #B85C38; }

/* ── Zone spacing ─────────────────────────────────────── */
.zone-gap-sm  { margin-bottom: 24px; }
.zone-gap-md  { margin-bottom: 32px; }
.zone-gap-lg  { margin-bottom: 40px; }
.section-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 14px;
}

/* ── Zone 2: Right Now ────────────────────────────────── */
.now-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #F5F0E8;
    border: 1px solid #EDE8E0;
    border-radius: 14px;
    padding: 16px 20px;
    text-decoration: none;
    color: inherit;
    transition: background 0.13s;
}
.now-card:hover { background: #EDE8DC; }
.pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--forest);
    flex-shrink: 0;
    animation: pdot 2s ease-in-out infinite;
}
@keyframes pdot {
    0%, 100% { transform: scale(1);    box-shadow: 0 0 0 0   rgba(74,103,65,0.5); }
    50%       { transform: scale(1.35); box-shadow: 0 0 0 5px rgba(74,103,65,0);   }
}
.now-body {
    flex: 1;
    min-width: 0;
    font-size: 14px;
    color: var(--espresso);
    line-height: 1.5;
}
.now-name strong { font-weight: 600; }
.now-dot-sep { color: var(--muted); padding: 0 1px; }
.now-stats {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--muted);
}

/* ── Zone 3: Hero stat cards ──────────────────────────── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
.stat-card {
    background: #fff;
    border: 1px solid #EDE8E0;
    border-radius: var(--radius-default);
    padding: 20px 22px;
    box-shadow: 0 1px 3px rgba(28,24,20,0.06), 0 4px 16px rgba(28,24,20,0.04);
    display: block;
    color: inherit;
    text-decoration: none;
    cursor: pointer;
    position: relative;
}
.stat-eye {
    position: absolute;
    top: 12px;
    right: 14px;
    color: #8C7B6E;
    line-height: 0;
}
.eye-closed { display: inline-block; }
.eye-open   { display: none; }
body.hero-revealed .eye-closed { display: none; }
body.hero-revealed .eye-open   { display: inline-block; }
.stat-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
    line-height: 1;
    display: block;
    margin-bottom: 10px;
}
.stat-value {
    font-family: "DM Mono", monospace;
    font-size: 32px;
    font-weight: 500;
    color: var(--espresso);
    line-height: 1;
    margin-bottom: 8px;
    letter-spacing: -0.01em;
}
.stat-value.c-forest { color: var(--forest); }
.stat-value.c-clay   { color: var(--clay); }
.stat-value.c-muted  { color: var(--muted); }
.stat-sub { font-size: 13px; color: var(--muted); }
.stat-credit-link {
    font-size: 12px;
    color: var(--terracotta);
    font-weight: 500;
    text-decoration: none;
}
/* Hero cards blur */
.stat-amount {
    filter: blur(8px);
    transition: filter 300ms ease;
    user-select: none;
    display: inline-block;
}
body.hero-revealed .stat-amount {
    filter: blur(0);
}

/* ── Zone 4: Summary cards ────────────────────────────── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
.summary-card {
    background: #FFFFFF;
    border: 1px solid #EDE8E0;
    border-radius: 14px;
    padding: 20px 22px;
    box-shadow: 0 1px 4px rgba(28,24,20,0.05);
    min-height: 140px;
    display: flex;
    flex-direction: column;
}
.sc-header {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
}
.sc-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.sc-dot-green { background: #4A6741; }
.sc-dot-amber { background: #C17F4A; }
.sc-dot-red   { background: #B85C38; }
.sc-title {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #8C7B6E;
    line-height: 1;
}
.sc-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.sc-line {
    font-size: 14px;
    color: var(--espresso);
    line-height: 1.6;
    margin: 0;
}
.sc-line strong { font-weight: 600; }
.sc-mono { font-family: "DM Mono", monospace; }
.sc-positive { color: #4A6741; font-weight: 500; }
.sc-footer {
    margin-top: auto;
    padding-top: 12px;
}
.sc-link {
    font-size: 12px;
    color: #C17F4A;
    font-weight: 500;
    text-decoration: none;
    transition: opacity 0.13s;
}
.sc-link:hover { opacity: 0.75; }

/* ── Zone 5: Recent Shifts ────────────────────────────── */
.shift-list {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(28,24,20,0.06);
    margin-bottom: 10px;
}
.shift-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    border-bottom: 1px solid #F5F0EB;
    text-decoration: none;
    color: inherit;
    transition: background 0.12s;
    min-height: 56px;
    border-left: 3px solid transparent;
}
.shift-row:last-child { border-bottom: none; }
.shift-row:hover { background: #FDFAF7; }
.sr-open { border-left-color: var(--forest); }
.shift-staff { font-weight: 600; font-size: 15px; color: var(--espresso); min-width: 120px; flex-shrink: 0; }
.shift-center { flex: 1; min-width: 0; }
.shift-meta {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--espresso);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.shift-split { font-size: 11.5px; color: var(--muted); margin-top: 3px; }
.shift-right { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
.shift-date { font-size: 12px; color: var(--muted); }
.shift-balanced {
    display: flex; align-items: center; gap: 4px;
    font-size: 12.5px; color: var(--forest);
}
.shift-balanced .dot { width: 6px; height: 6px; background: var(--forest); border-radius: 50%; flex-shrink: 0; }
.shift-disc { font-size: 15px; font-weight: 700; color: var(--clay); }
.shift-disc.over { color: var(--terracotta); }
.badge-open {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    background: #DFF0DD;
    color: var(--forest);
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    font-family: "Plus Jakarta Sans", sans-serif;
}
.see-all-link {
    display: block;
    text-align: right;
    font-size: 13px;
    color: var(--terracotta);
    font-weight: 600;
    padding: 4px 0;
    transition: opacity 0.13s;
}
.see-all-link:hover { opacity: 0.75; }

/* ── Zone 6: Quick Actions ────────────────────────────── */
.quick-actions { display: flex; align-items: center; gap: 28px; flex-wrap: wrap; }
.quick-link { font-size: 13px; color: var(--muted); text-decoration: none; transition: color 0.13s; }
.quick-link:hover { color: var(--espresso); }

/* ── Mobile ───────────────────────────────────────────── */
@media (max-width: 767px) {
    .dash-greeting { font-size: 22px; }

    /* Right Now — two lines */
    .now-dot-sep { display: none; }
    .now-stats   { display: block; }

    /* Stat grid — 2x2 */
    .stat-grid  { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .stat-value { font-size: 24px; }

    /* Summary cards — stack on mobile */
    .summary-grid { grid-template-columns: 1fr; gap: 12px; }

    /* Shifts */
    .shift-split { display: none; }
    .shift-staff { min-width: 90px; }
    .shift-disc  { font-size: 13px; }
}
</style>
@endsection

@section('content')

@php
/* ─── Card 1: Your Shop ───────────────────────────────── */
$shopDot   = 'green';
$shopLines = [];

if ($discrepancyShifts->isNotEmpty()) {
    $shopDot = 'red';
} elseif ($openShifts->isNotEmpty()) {
    if ($openShifts->first()->opened_at->diffInHours(now()) >= 10) {
        $shopDot = 'amber';
    }
}

if ($openShifts->isNotEmpty()) {
    $osI   = $openShifts->first();
    $osCnt = $osI->activeSales->count();
    $shopLines[] = ['type' => 'open', 'staff' => $osI->staff->name, 'count' => $osCnt];
}
if ($discrepancyShifts->isNotEmpty()) {
    $dsI   = $discrepancyShifts->first();
    $disc  = abs((float)$dsI->cash_discrepancy);
    $dtype = (float)$dsI->cash_discrepancy < 0 ? 'short' : 'over';
    $when  = $dsI->closed_at->isYesterday() ? 'yesterday'
           : 'on ' . $dsI->closed_at->format('d M');
    $shopLines[] = ['type' => 'disc', 'staff' => $dsI->staff->name,
                    'disc' => $disc, 'dtype' => $dtype, 'when' => $when];
}

/* ─── Card 2: Money ───────────────────────────────────── */
$supplierTotal = $supplierBalances->sum('total');
$maxCreditDays = 0;
foreach ($overdueCredit as $ocl) {
    $d = (int)$ocl->created_at->diffInDays(now());
    if ($d > $maxCreditDays) $maxCreditDays = $d;
}
$moneyDot = 'green';
if ($creditOwed > 0 || $supplierTotal > 0) {
    $moneyDot = $maxCreditDays >= 60 ? 'red' : 'amber';
}

/* ─── Card 3: Stock ───────────────────────────────────── */
$oosProductIds = collect()
    ->merge($outOfStock->pluck('id'))
    ->merge($outVariants->pluck('product_id'))
    ->unique();
$oosCount  = $oosProductIds->count();

$lowProductIds = collect()
    ->merge($lowStock->pluck('id'))
    ->merge($lowVariants->pluck('product_id'))
    ->merge($lowBottles->pluck('product_id'))
    ->unique()
    ->diff($oosProductIds);
$lowCount  = $lowProductIds->count();

$stockDot = 'green';
if ($oosCount > 0)       $stockDot = 'red';
elseif ($lowCount > 0)   $stockDot = 'amber';
@endphp

<div class="dash-wrap">

{{-- ── Zone 2: Right Now (hidden if no open shift) ─────── --}}
@if($openShifts->isNotEmpty())
@php
    $os      = $openShifts->first();
    $osSales = $os->activeSales;
    $osTotal = $osSales->sum('total');
    $osCount = $osSales->count();
    $since   = $os->opened_at->format('g:ia');
@endphp
<div class="zone-gap-sm">
    <a href="/shifts/{{ $os->id }}" class="now-card">
        <span class="pulse-dot"></span>
        <div class="now-body">
            <span class="now-name"><strong>{{ $os->staff->name }}</strong> is selling</span><span class="now-dot-sep"> · </span><span class="now-stats">{{ $osCount }} {{ $osCount === 1 ? 'sale' : 'sales' }} · Ksh {{ number_format((int)$osTotal) }} so far · Since {{ $since }}</span>
        </div>
    </a>
</div>
@endif

{{-- ── Zone 3: Hero stat cards ───────────────────────────── --}}
@php
    $cmpDiff = (int)$todayTotal - (int)$yesterdayTotal;
    $cmpClass = $cmpDiff > 0 ? 'cmp-up' : 'cmp-down';
    $cmpArrow = $cmpDiff > 0 ? '↑' : '↓';
    $cmpLabel = $cmpDiff > 0 ? 'more' : 'less';
    $showCmp  = $yesterdayTotal > 0 && $cmpDiff !== 0;
@endphp
<div class="stat-grid zone-gap-md">

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">Today's sales</span>
        <div class="stat-value stat-amount">Ksh&nbsp;{{ number_format((int)$todayTotal) }}</div>
        <div class="stat-sub">{{ $todayCount }} {{ $todayCount === 1 ? 'transaction' : 'transactions' }}</div>
        @if($showCmp)
        <div class="stat-compare {{ $cmpClass }}">{{ $cmpArrow }} <span class="cmp-num">Ksh {{ number_format(abs($cmpDiff)) }}</span> {{ $cmpLabel }} than yesterday</div>
        @endif
    </div>

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">Cash collected</span>
        <div class="stat-value c-forest stat-amount">Ksh&nbsp;{{ number_format((int)$todayCash) }}</div>
    </div>

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">M-Pesa received</span>
        <div class="stat-value c-forest stat-amount">Ksh&nbsp;{{ number_format((int)$todayMpesa) }}</div>
    </div>

    <div class="stat-card" onclick="toggleHero()">
        <div class="stat-eye">
            <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <span class="stat-label">Customers owe you</span>
        <div class="stat-value {{ $creditOwed > 0 ? 'c-clay' : 'c-muted' }} stat-amount">Ksh&nbsp;{{ number_format((int)$creditOwed) }}</div>
        @if($creditOwed > 0)
        <div class="stat-sub"><a href="/credit" class="stat-credit-link">View all credit →</a></div>
        @endif
    </div>

</div>
<p id="hero-hint" style="text-align:center; font-size:11px; color:#8C7B6E; margin-top:6px;">Tap any card to show amounts</p>

{{-- ── Zone 5: Recent Shifts ──────────────────────────── --}}
<section class="zone-gap-lg">
    <h2 class="section-title">Recent shifts</h2>
    <div class="shift-list">

        {{-- Open shift at top --}}
        @if($openShifts->isNotEmpty())
        @php
            $os2      = $openShifts->first();
            $os2Sales = $os2->activeSales;
            $os2Total = $os2Sales->sum('total');
        @endphp
        <a href="/shifts/{{ $os2->id }}" class="shift-row sr-open">
            <div class="shift-staff">{{ $os2->staff->name }}</div>
            <div class="shift-center">
                <div class="shift-meta">
                    <span class="badge-open">Open now</span>
                    <span>{{ $os2Sales->count() }} {{ $os2Sales->count() === 1 ? 'sale' : 'sales' }}</span>
                    <span style="color:var(--border);">·</span>
                    <span>Ksh {{ number_format((int)$os2Total) }}</span>
                </div>
            </div>
            <div class="shift-right">
                <span class="shift-date" style="color:var(--forest);font-weight:500;">Since {{ $os2->opened_at->format('g:ia') }}</span>
            </div>
        </a>
        @endif

        {{-- Closed shifts --}}
        @foreach($recentShifts as $shift)
        @php
            $ss     = $shift->activeSales;
            $sTotal = $ss->sum('total');
            $sCash  = $ss->where('payment_type', 'cash')->sum('total');
            $sMpesa = $ss->where('payment_type', 'mpesa')->sum('total');
            $sCount = $ss->count();
            $disc   = (float)$shift->cash_discrepancy;
            $sDate  = $shift->closed_at->isYesterday() ? 'Yesterday'
                    : ($shift->closed_at->isToday()     ? 'Today'
                    : $shift->closed_at->format('d M'));
        @endphp
        <a href="/shifts/{{ $shift->id }}" class="shift-row">
            <div class="shift-staff">{{ $shift->staff->name }}</div>
            <div class="shift-center">
                <div class="shift-meta">
                    <span>{{ $sCount }} {{ $sCount === 1 ? 'sale' : 'sales' }}</span>
                    <span style="color:var(--border);">·</span>
                    <span>Ksh {{ number_format((int)$sTotal) }}</span>
                </div>
                <div class="shift-split">
                    Cash Ksh {{ number_format((int)$sCash) }}
                    &nbsp;·&nbsp;M-Pesa Ksh {{ number_format((int)$sMpesa) }}
                </div>
            </div>
            <div class="shift-right">
                <span class="shift-date">{{ $sDate }}</span>
                @if($disc == 0)
                    <span class="shift-balanced"><span class="dot"></span>Balanced</span>
                @elseif($disc < 0)
                    <span class="shift-disc">Ksh {{ number_format(abs($disc), 0) }} short</span>
                @else
                    <span class="shift-disc over">Ksh {{ number_format(abs($disc), 0) }} over</span>
                @endif
            </div>
        </a>
        @endforeach

    </div>
    <a href="/shifts" class="see-all-link">See all shifts →</a>
</section>

{{-- ── Zone 4: Summary cards ─────────────────────────── --}}
<div class="summary-grid zone-gap-lg">

    {{-- Card 1: Your Shop --}}
    <div class="summary-card">
        <div class="sc-header">
            <span class="sc-dot sc-dot-{{ $shopDot }}"></span>
            <span class="sc-title">Your shop</span>
        </div>
        <div class="sc-body">
            @if(empty($shopLines))
                <p class="sc-line sc-positive">All recent shifts balanced</p>
            @else
                @foreach($shopLines as $sl)
                    @if($sl['type'] === 'open')
                    <p class="sc-line">
                        <strong>{{ $sl['staff'] }}</strong> is selling
                        · <span class="sc-mono">{{ $sl['count'] }}</span>
                        {{ $sl['count'] === 1 ? 'sale' : 'sales' }} so far
                    </p>
                    @elseif($sl['type'] === 'disc')
                    <p class="sc-line">
                        <strong>{{ $sl['staff'] }}</strong> was
                        <span style="color:#B85C38;">Ksh {{ number_format($sl['disc'], 0) }} {{ $sl['dtype'] }}</span>
                        {{ $sl['when'] }}
                    </p>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="sc-footer">
            <a href="/shifts" class="sc-link">See all shifts →</a>
        </div>
    </div>

    {{-- Card 2: Money --}}
    <div class="summary-card">
        <div class="sc-header">
            <span class="sc-dot sc-dot-{{ $moneyDot }}"></span>
            <span class="sc-title">Money</span>
        </div>
        <div class="sc-body">
            @if($creditOwed == 0 && $supplierTotal == 0)
                <p class="sc-line sc-positive">No outstanding balances</p>
            @else
                @if($creditOwed > 0)
                <p class="sc-line" style="color:#B85C38;">
                    Ksh <span class="sc-mono">{{ number_format((int)$creditOwed) }}</span>
                    owed by customers
                </p>
                @endif
                @if($supplierTotal > 0)
                <p class="sc-line" style="color:#C17F4A;">
                    Ksh <span class="sc-mono">{{ number_format((int)$supplierTotal) }}</span>
                    owed to suppliers
                </p>
                @endif
            @endif
        </div>
        <div class="sc-footer">
            <a href="/credit" class="sc-link">See all credit →</a>
        </div>
    </div>

    {{-- Card 3: Stock --}}
    <div class="summary-card">
        <div class="sc-header">
            <span class="sc-dot sc-dot-{{ $stockDot }}"></span>
            <span class="sc-title">Stock</span>
        </div>
        <div class="sc-body">
            @if($oosCount == 0 && $lowCount == 0)
                <p class="sc-line sc-positive">Stock levels looking good</p>
            @elseif($oosCount > 0)
                <p class="sc-line">
                    <span style="color:#B85C38;">{{ $oosCount }} {{ $oosCount === 1 ? 'item' : 'items' }} sold out</span>
                    — restock needed
                </p>
                @if($lowCount > 0)
                <p class="sc-line" style="color:#C17F4A;">{{ $lowCount }} more running low</p>
                @endif
            @else
                <p class="sc-line" style="color:#C17F4A;">{{ $lowCount }} {{ $lowCount === 1 ? 'item' : 'items' }} running low</p>
            @endif
        </div>
        <div class="sc-footer">
            <a href="/products" class="sc-link">See stock report →</a>
        </div>
    </div>

</div>

{{-- ── Zone 6: Quick Actions ──────────────────────────── --}}
<div class="quick-actions">
    <a href="/credit" class="quick-link">View all credit →</a>
    <a href="/shopping-list" class="quick-link">Shopping list →</a>
    <a href="/restocks" class="quick-link">Restocks →</a>
    <a href="/supplier-balances" class="quick-link">Supplier balances →</a>
    <a href="/products" class="quick-link">Stock report →</a>
</div>

</div>{{-- /dash-wrap --}}

@endsection

@section('scripts')
<script>
(function () {
    window.toggleHero = function () {
        document.body.classList.toggle('hero-revealed');
        var hint = document.getElementById('hero-hint');
        if (hint) {
            hint.style.display = 'none';
            sessionStorage.setItem('stoka_hero_hint', '1');
        }
    };
    if (sessionStorage.getItem('stoka_hero_hint') === '1') {
        var hint = document.getElementById('hero-hint');
        if (hint) hint.style.display = 'none';
    }
})();
</script>
@endsection
