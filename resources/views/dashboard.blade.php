@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
    <div>
        <p class="dash-greeting">{{ $greeting }}, {{ session('auth_name') }}.</p>
        <p class="dash-date">{{ $today->isoFormat('dddd, D MMMM YYYY') }}</p>
    </div>
    <button class="eye-btn" id="reveal-btn" onclick="toggleAmounts()" title="Show amounts" aria-label="Show amounts">
        <svg id="eye-closed" width="22" height="22" viewBox="0 0 22 22" fill="none">
            <path d="M3 11s3.5-6 8-6 8 6 8 6-3.5 6-8 6-8-6-8-6Z" stroke="#8C7B6E" stroke-width="1.5"/>
            <circle cx="11" cy="11" r="2.5" stroke="#8C7B6E" stroke-width="1.5"/>
            <path d="M3 3l16 16" stroke="#8C7B6E" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <svg id="eye-open" width="22" height="22" viewBox="0 0 22 22" fill="none" style="display:none;">
            <path d="M3 11s3.5-6 8-6 8 6 8 6-3.5 6-8 6-8-6-8-6Z" stroke="#1C1814" stroke-width="1.5"/>
            <circle cx="11" cy="11" r="2.5" stroke="#1C1814" stroke-width="1.5"/>
        </svg>
    </button>
</div>
@endsection

@section('styles')
<style>
    /* ── Zone 1: Greeting ───────────────────────────────── */
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
    .eye-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        flex-shrink: 0;
        margin-top: 2px;
        transition: background 0.13s;
        line-height: 0;
    }
    .eye-btn:hover { background: var(--border); }

    /* ── Number masking ─────────────────────────────────── */
    .amt-r { display: none; }
    .amt-h { display: inline; }
    body.amounts-revealed .amt-r { display: inline; }
    body.amounts-revealed .amt-h { display: none; }

    /* ── Layout helpers ─────────────────────────────────── */
    .dash-section   { margin-bottom: 36px; }
    .section-title  {
        font-family: "Cormorant Garamond", serif;
        font-size: 20px;
        font-weight: 600;
        color: var(--espresso);
        margin-bottom: 14px;
    }

    /* ── Zone 2: Right Now ──────────────────────────────── */
    .zone-now { margin-bottom: 28px; }
    .now-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #EDE8E0;
        border: 1px solid #D8D0C4;
        border-radius: 12px;
        padding: 18px 22px;
        text-decoration: none;
        color: inherit;
        transition: background 0.13s;
        flex-wrap: wrap;
    }
    .now-card:hover { background: #E5DFD5; }
    .now-left {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
        color: var(--espresso);
        flex-shrink: 0;
    }
    .pulse-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: var(--forest);
        flex-shrink: 0;
        animation: pulse-ring 2s ease-in-out infinite;
    }
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(74,103,65,0.55); }
        70%  { box-shadow: 0 0 0 7px rgba(74,103,65,0); }
        100% { box-shadow: 0 0 0 0 rgba(74,103,65,0); }
    }
    .now-centre {
        flex: 1;
        font-size: 14px;
        color: var(--espresso);
        min-width: 0;
    }
    .now-centre .mono { font-family: "DM Mono", monospace; font-size: 14px; font-weight: 500; }
    .now-right { font-size: 12.5px; color: var(--muted); white-space: nowrap; flex-shrink: 0; }
    .no-shift-msg { font-size: 13.5px; color: var(--muted); padding: 14px 0; }

    /* ── Zone 3: Stat cards ─────────────────────────────── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px 22px 18px;
        box-shadow: 0 1px 3px rgba(28,24,20,0.06), 0 4px 12px rgba(28,24,20,0.04);
        text-decoration: none;
        display: block;
    }
    .stat-value {
        font-family: "DM Mono", monospace;
        font-size: 26px;
        font-weight: 500;
        color: var(--espresso);
        line-height: 1;
        margin-bottom: 8px;
    }
    .stat-value.c-forest { color: var(--forest); }
    .stat-value.c-clay   { color: var(--clay); }
    .stat-value.c-muted  { color: var(--muted); }
    .stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        margin-bottom: 4px;
    }
    .stat-sub { font-size: 12px; color: var(--muted); }

    /* ── Zone 4: Alert rows ─────────────────────────────── */
    .alert-list { display: flex; flex-direction: column; gap: 2px; }
    .alert-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 16px;
        background: var(--parchment);
        border-radius: 8px;
        border-left: 4px solid transparent;
        text-decoration: none;
        color: inherit;
        transition: background 0.12s;
        min-height: 48px;
    }
    .alert-row:hover { background: #EDE8E0; }
    .alert-icon { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.8; }
    .alert-text { flex: 1; font-size: 13.5px; color: var(--espresso); line-height: 1.4; }
    .alert-text strong { font-weight: 600; }
    .alert-age { font-size: 12px; color: var(--muted); white-space: nowrap; flex-shrink: 0; }
    .b-red        { border-left-color: var(--clay); }
    .b-amber      { border-left-color: #C4850A; }
    .b-terracotta { border-left-color: var(--terracotta); }
    .b-muted      { border-left-color: var(--border); }

    /* ── Zone 5: Shift rows ─────────────────────────────── */
    .shift-list {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(28,24,20,0.06);
        margin-bottom: 10px;
    }
    .shift-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        text-decoration: none;
        color: inherit;
        transition: background 0.12s;
        min-height: 54px;
    }
    .shift-row:last-child { border-bottom: none; }
    .shift-row:hover { background: #FAF5EF; }
    .shift-staff { font-weight: 600; font-size: 13.5px; color: var(--espresso); min-width: 120px; flex-shrink: 0; }
    .shift-meta  { flex: 1; font-family: "DM Mono", monospace; font-size: 13px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .shift-split { font-size: 11.5px; color: var(--muted); margin-top: 2px; }
    .shift-right { display: flex; flex-direction: column; align-items: flex-end; gap: 3px; flex-shrink: 0; }
    .shift-date  { font-size: 12px; color: var(--muted); }
    .shift-balanced {
        display: flex; align-items: center; gap: 4px;
        font-size: 12px; color: var(--forest);
    }
    .shift-balanced .dot { width: 6px; height: 6px; background: var(--forest); border-radius: 50%; flex-shrink: 0; }
    .shift-disc { font-size: 12.5px; font-weight: 700; color: var(--clay); }
    .shift-disc.over { color: var(--terracotta); }
    .see-all-link { font-size: 13px; color: var(--muted); display: inline-block; padding: 4px 0; transition: color 0.13s; }
    .see-all-link:hover { color: var(--espresso); }

    /* ── Zone 6: Quick actions ──────────────────────────── */
    .quick-actions { display: flex; align-items: center; gap: 28px; padding-top: 8px; flex-wrap: wrap; }
    .quick-link { font-size: 13px; color: var(--muted); text-decoration: none; transition: color 0.13s; }
    .quick-link:hover { color: var(--espresso); }

    /* ── Mobile ─────────────────────────────────────────── */
    @media (max-width: 767px) {
        .dash-greeting   { font-size: 22px; }
        .stat-grid       { grid-template-columns: repeat(2,1fr); gap: 10px; }
        .stat-value      { font-size: 20px; }
        .now-right       { display: none; }
        .shift-split     { display: none; }
        .shift-meta      { font-size: 12px; }
        .shift-staff     { min-width: 90px; }
    }
</style>
@endsection

@section('content')

@php
    $hasAlerts = $discrepancyShifts->isNotEmpty()
        || $overdueCredit->isNotEmpty()
        || $outOfStock->isNotEmpty()
        || $outVariants->isNotEmpty()
        || $supplierBalances->isNotEmpty()
        || $lowVariants->isNotEmpty()
        || $lowBottles->isNotEmpty()
        || $recentCredit->isNotEmpty();
@endphp

{{-- ── Zone 2: Right Now ──────────────────────────────── --}}
<div class="zone-now">
    @if($openShifts->isNotEmpty())
    @php
        $os       = $openShifts->first();
        $osSales  = $os->activeSales;
        $osTotal  = $osSales->sum('total');
        $osCount  = $osSales->count();
        $since    = $os->opened_at->format('g:ia');
        $diffMins = (int)$os->opened_at->diffInMinutes(now());
        $dh       = intdiv($diffMins, 60);
        $dm       = $diffMins % 60;
        $dur      = $dh > 0 ? "{$dh}h {$dm}m" : "{$dm}m";
    @endphp
    <a href="/shifts/{{ $os->id }}" class="now-card">
        <div class="now-left">
            <span class="pulse-dot"></span>
            {{ $os->staff->name }} is selling right now
        </div>
        <div class="now-centre">
            <span class="mono">{{ $osCount }} {{ $osCount === 1 ? 'sale' : 'sales' }}</span>
            &nbsp;·&nbsp;Ksh&nbsp;<span class="amt-h">••••••</span><span class="amt-r countup" data-value="{{ (int)$osTotal }}">{{ number_format((int)$osTotal) }}</span> so far today
        </div>
        <div class="now-right">Selling since {{ $since }} · {{ $dur }}</div>
    </a>
    @else
    <p class="no-shift-msg">No shift open right now.</p>
    @endif
</div>

{{-- ── Zone 3: Today's numbers ────────────────────────── --}}
<div class="stat-grid dash-section">

    <div class="stat-card">
        <div class="stat-value">Ksh&nbsp;<span class="amt-h">••••••</span><span class="amt-r countup" data-value="{{ (int)$todayTotal }}">{{ number_format((int)$todayTotal) }}</span></div>
        <div class="stat-label">Today's sales</div>
        <div class="stat-sub">{{ $todayCount }} {{ $todayCount === 1 ? 'transaction' : 'transactions' }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-value c-forest">Ksh&nbsp;<span class="amt-h">••••••</span><span class="amt-r countup" data-value="{{ (int)$todayCash }}">{{ number_format((int)$todayCash) }}</span></div>
        <div class="stat-label">Cash collected</div>
        <div class="stat-sub">&nbsp;</div>
    </div>

    <div class="stat-card">
        <div class="stat-value c-forest">Ksh&nbsp;<span class="amt-h">••••••</span><span class="amt-r countup" data-value="{{ (int)$todayMpesa }}">{{ number_format((int)$todayMpesa) }}</span></div>
        <div class="stat-label">M-Pesa received</div>
        <div class="stat-sub">&nbsp;</div>
    </div>

    <a href="/credit" class="stat-card" style="cursor:pointer;">
        <div class="stat-value {{ $creditOwed > 0 ? 'c-clay' : 'c-muted' }}">Ksh&nbsp;<span class="amt-h">••••••</span><span class="amt-r countup" data-value="{{ (int)$creditOwed }}">{{ number_format((int)$creditOwed) }}</span></div>
        <div class="stat-label">Customers owe you</div>
        <div class="stat-sub" style="{{ $creditOwed > 0 ? 'color:var(--clay);font-weight:500;' : '' }}">{{ $creditOwed > 0 ? 'Tap to view' : 'All clear' }}</div>
    </a>

</div>

{{-- ── Zone 4: Needs your attention ───────────────────── --}}
@if($hasAlerts)
<section class="dash-section">
    <h2 class="section-title">Needs your attention</h2>
    <div class="alert-list">

        {{-- Shift discrepancies --}}
        @foreach($discrepancyShifts as $s)
        @php
            $disc     = abs((float)$s->cash_discrepancy);
            $dtype    = (float)$s->cash_discrepancy < 0 ? 'short' : 'over';
            $when     = $s->closed_at->isYesterday() ? 'yesterday' : $s->closed_at->diffForHumans();
            $closeAt  = $s->closed_at->format('g:ia');
        @endphp
        <a href="/shifts/{{ $s->id }}" class="alert-row b-red">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none">
                <path d="M9 2L1.5 15.5h15L9 2Z" stroke="var(--clay)" stroke-width="1.4" stroke-linejoin="round"/>
                <path d="M9 7v4" stroke="var(--clay)" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="9" cy="13" r="0.7" fill="var(--clay)"/>
            </svg>
            <span class="alert-text">
                <strong>{{ $s->staff->name }}</strong> was Ksh {{ number_format($disc, 0) }} {{ $dtype }} {{ $when }} · Shift closed at {{ $closeAt }}
            </span>
            <span class="alert-age">{{ $s->closed_at->format('d M') }}</span>
        </a>
        @endforeach

        {{-- Overdue credit (>30 days) --}}
        @foreach($overdueCredit as $ledger)
        @php $days = (int)$ledger->created_at->diffInDays(now()); @endphp
        <a href="/credit" class="alert-row b-red">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none">
                <rect x="1.5" y="4" width="15" height="10" rx="1.5" stroke="var(--clay)" stroke-width="1.4"/>
                <path d="M1.5 7.5h15" stroke="var(--clay)" stroke-width="1.3"/>
                <path d="M5 11.5h4" stroke="var(--clay)" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            <span class="alert-text">
                <strong>{{ $ledger->customer->name }}</strong> owes Ksh <span class="amt-h">••••••</span><span class="amt-r">{{ number_format($ledger->balance, 0) }}</span> · {{ $days }} days · Nothing paid yet
            </span>
            <span class="alert-age">{{ $days }}d overdue</span>
        </a>
        @endforeach

        {{-- Out of stock: unit products --}}
        @foreach($outOfStock as $product)
        <a href="{{ route('products.edit', $product) }}" class="alert-row b-red">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none">
                <circle cx="9" cy="9" r="6.5" stroke="var(--clay)" stroke-width="1.4"/>
                <path d="M6 6l6 6M12 6l-6 6" stroke="var(--clay)" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            <span class="alert-text"><strong>{{ $product->name }}</strong> · Out of stock</span>
            <span class="alert-age">Restock needed</span>
        </a>
        @endforeach

        {{-- Out of stock: variants --}}
        @foreach($outVariants as $variant)
        <a href="{{ route('products.edit', $variant->product) }}" class="alert-row b-red">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none">
                <circle cx="9" cy="9" r="6.5" stroke="var(--clay)" stroke-width="1.4"/>
                <path d="M6 6l6 6M12 6l-6 6" stroke="var(--clay)" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            <span class="alert-text">
                <strong>{{ $variant->product->name }}</strong> · Size {{ $variant->size }}{{ $variant->colour ? ' / '.$variant->colour : '' }} · Out of stock
            </span>
            <span class="alert-age">Restock needed</span>
        </a>
        @endforeach

        {{-- Supplier balances --}}
        @foreach($supplierBalances as $bal)
        @php $age = (int)\Carbon\Carbon::parse($bal->since)->diffInDays(now()); @endphp
        <a href="/suppliers" class="alert-row b-terracotta">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none">
                <path d="M2 14V8l7-4.5L16 8v6a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1Z" stroke="var(--terracotta)" stroke-width="1.4"/>
                <path d="M7 15V11h4v4" stroke="var(--terracotta)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="alert-text">
                <strong>{{ $bal->name }}</strong> · Ksh <span class="amt-h">••••••</span><span class="amt-r">{{ number_format((float)$bal->total, 0) }}</span> still owed
            </span>
            <span class="alert-age">{{ $age }} days</span>
        </a>
        @endforeach

        {{-- Low stock: variants --}}
        @foreach($lowVariants as $variant)
        <a href="{{ route('products.edit', $variant->product) }}" class="alert-row b-amber">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none">
                <path d="M9 2L1.5 15.5h15L9 2Z" stroke="#C4850A" stroke-width="1.4" stroke-linejoin="round"/>
                <path d="M9 7v4" stroke="#C4850A" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="9" cy="13" r="0.7" fill="#C4850A"/>
            </svg>
            <span class="alert-text">
                <strong>{{ $variant->product->name }}</strong> · Size {{ $variant->size }}{{ $variant->colour ? ' / '.$variant->colour : '' }} · Only {{ $variant->stock }} left
            </span>
            <span class="alert-age">Running low</span>
        </a>
        @endforeach

        {{-- Low stock: measured bottles --}}
        @foreach($lowBottles as $bottle)
        @php $pct = round($bottle->remaining_ml / $bottle->total_ml * 100); @endphp
        <a href="{{ route('products.edit', $bottle->product) }}" class="alert-row b-amber">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none">
                <path d="M7.5 2h3v2.5L13 7v8a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7l2.5-2.5V2Z" stroke="#C4850A" stroke-width="1.3"/>
                <path d="M5 11h8" stroke="#C4850A" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            <span class="alert-text">
                <strong>{{ $bottle->product->name }}</strong> · {{ number_format($bottle->remaining_ml, 0) }}ml remaining · Nearly empty
            </span>
            <span class="alert-age">{{ $pct }}% full</span>
        </a>
        @endforeach

        {{-- Recent credit (today's new tabs) --}}
        @foreach($recentCredit as $ledger)
        <a href="/credit" class="alert-row b-muted">
            <svg class="alert-icon" viewBox="0 0 18 18" fill="none" style="opacity:0.5;">
                <circle cx="9" cy="7" r="3" stroke="var(--muted)" stroke-width="1.3"/>
                <path d="M3 16c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="var(--muted)" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            <span class="alert-text" style="color:var(--muted);">
                <strong style="color:var(--espresso);">{{ $ledger->customer->name }}</strong> started a credit tab today · Ksh <span class="amt-h">••••••</span><span class="amt-r">{{ number_format($ledger->amount, 0) }}</span>
            </span>
            <span class="alert-age">Today</span>
        </a>
        @endforeach

    </div>
</section>
@endif

{{-- ── Zone 5: Recent shifts ───────────────────────────── --}}
@if($recentShifts->isNotEmpty())
<section class="dash-section">
    <h2 class="section-title">Recent shifts</h2>
    <div class="shift-list">
        @foreach($recentShifts as $shift)
        @php
            $ss      = $shift->activeSales;
            $sTotal  = $ss->sum('total');
            $sCash   = $ss->where('payment_type','cash')->sum('total');
            $sMpesa  = $ss->where('payment_type','mpesa')->sum('total');
            $sCount  = $ss->count();
            $disc    = (float)$shift->cash_discrepancy;
            $sDate   = $shift->closed_at->isYesterday() ? 'Yesterday'
                       : ($shift->closed_at->isToday() ? 'Today'
                       : $shift->closed_at->format('d M'));
        @endphp
        <a href="/shifts/{{ $shift->id }}" class="shift-row">
            <div class="shift-staff">{{ $shift->staff->name }}</div>
            <div style="flex:1;min-width:0;">
                <div class="shift-meta">
                    <span>{{ $sCount }} {{ $sCount === 1 ? 'sale' : 'sales' }}</span>
                    <span style="color:var(--border);">·</span>
                    <span>Ksh <span class="amt-h">••••••</span><span class="amt-r countup" data-value="{{ (int)$sTotal }}">{{ number_format((int)$sTotal) }}</span></span>
                </div>
                <div class="shift-split">
                    Cash Ksh <span class="amt-h">•••</span><span class="amt-r">{{ number_format((int)$sCash) }}</span>
                    &nbsp;·&nbsp;
                    M-Pesa Ksh <span class="amt-h">•••</span><span class="amt-r">{{ number_format((int)$sMpesa) }}</span>
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
@endif

{{-- ── Zone 6: Quick actions ───────────────────────────── --}}
<div class="quick-actions">
    <a href="/credit" class="quick-link">View all credit →</a>
    <a href="/shopping-list" class="quick-link">Shopping list →</a>
    <a href="/products" class="quick-link">Stock report →</a>
</div>

@endsection

@section('scripts')
<script>
(function () {
    function updateEyeIcon(open) {
        document.getElementById('eye-closed').style.display = open ? 'none'   : 'inline';
        document.getElementById('eye-open').style.display   = open ? 'inline' : 'none';
    }

    // Restore session preference
    if (sessionStorage.getItem('stoka_reveal') === '1') {
        document.body.classList.add('amounts-revealed');
        updateEyeIcon(true);
    }

    window.toggleAmounts = function () {
        var revealing = document.body.classList.toggle('amounts-revealed');
        sessionStorage.setItem('stoka_reveal', revealing ? '1' : '0');
        updateEyeIcon(revealing);
        if (revealing) animateCountups();
    };

    function animateCountups() {
        document.querySelectorAll('.countup').forEach(function (el, i) {
            var target = parseFloat(el.dataset.value) || 0;
            if (target === 0) return;
            var duration  = 600;
            var startTime = null;
            setTimeout(function () {
                function frame(ts) {
                    if (!startTime) startTime = ts;
                    var progress = Math.min((ts - startTime) / duration, 1);
                    var eased    = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(eased * target).toLocaleString();
                    if (progress < 1) { requestAnimationFrame(frame); }
                    else { el.textContent = target.toLocaleString(); }
                }
                requestAnimationFrame(frame);
            }, i * 80);
        });
    }
})();
</script>
@endsection
