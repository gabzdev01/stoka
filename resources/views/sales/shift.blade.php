@extends('layouts.staff')

@section('title', 'My Shift')

@section('styles')
<style>
/* ── Wrap ───────────────────────────────────────── */
.myshift-wrap {
    padding: 16px 16px 40px;
}

/* ── No-shift state ─────────────────────────────── */
.no-shift-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 12px;
    padding: 80px 20px;
}
.no-shift-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 24px;
    font-weight: 600;
    color: var(--espresso);
}
.no-shift-sub {
    font-size: 14px;
    color: var(--muted);
}
.open-shift-link {
    display: inline-flex;
    align-items: center;
    height: 48px;
    padding: 0 28px;
    background: var(--espresso);
    color: #fff;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    margin-top: 8px;
    -webkit-tap-highlight-color: transparent;
}
.open-shift-link:active { opacity: 0.8; }

/* ── Shift header ───────────────────────────────── */
.shift-header {
    margin-bottom: 16px;
}
.shift-header-name {
    font-family: "Cormorant Garamond", serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.2;
}
.shift-header-since {
    font-size: 13px;
    color: var(--muted);
    margin-top: 3px;
}

/* ── Running totals ─────────────────────────────── */
.totals-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 20px;
}
.total-box {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.total-box-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
}
.total-box-val {
    font-family: "DM Mono", monospace;
    font-size: 18px;
    font-weight: 500;
    color: var(--espresso);
    line-height: 1;
}
#live-total    { color: var(--espresso); }
#live-cash     { color: var(--forest); }
#live-mpesa    { color: var(--forest); }

/* ── Sales list ─────────────────────────────────── */
.sales-section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
    margin-bottom: 10px;
}
.sales-list {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}

/* ── Empty shift ────────────────────────────────── */
.sales-empty {
    padding: 32px 16px;
    text-align: center;
    font-size: 13px;
    color: var(--muted);
    line-height: 1.6;
}

/* ── Sale row ───────────────────────────────────── */
.sale-item {
    border-bottom: 1px solid var(--border);
}
.sale-item:last-child { border-bottom: none; }

.sale-item-main {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    padding: 12px 14px;
}
.sale-item.voided .sale-item-main {
    opacity: 0.45;
}

.sale-item-left {
    flex: 1;
    min-width: 0;
}
.sale-item-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--espresso);
    line-height: 1.3;
}
.sale-item.voided .sale-item-name {
    text-decoration: line-through;
}
.sale-item-meta {
    font-family: "DM Mono", monospace;
    font-size: 12px;
    color: var(--muted);
    margin-top: 3px;
}
.sale-item-void-reason {
    font-size: 11px;
    color: var(--clay);
    margin-top: 3px;
    font-style: italic;
}

.sale-item-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
    flex-shrink: 0;
}
.sale-item-amount {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    font-weight: 500;
    color: var(--espresso);
}
.sale-item-time {
    font-size: 11px;
    color: var(--muted);
}

/* ── Exchange button ────────────────────────────── */
.exchange-btn {
    display: inline-flex;
    align-items: center;
    background: none;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-decoration: none;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: border-color 0.13s, color 0.13s;
    white-space: nowrap;
}
.exchange-btn:active { color: var(--terracotta); border-color: var(--terracotta); }

/* ── Payment badge ──────────────────────────────── */
.pay-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 2px 7px;
    border-radius: 10px;
}
.pay-cash   { background: #E8F0E6; color: var(--forest); }
.pay-mpesa  { background: #E8F0E6; color: var(--forest); }
.pay-credit   { background: #F5E0D8; color: var(--clay); }
.pay-exchange { background: #EBE3D8; color: var(--muted); }
.voided-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 2px 7px;
    border-radius: 10px;
    background: #F0EDED;
    color: #9A8A82;
}

/* ── Void button ────────────────────────────────── */
.void-btn {
    background: none;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: border-color 0.13s, color 0.13s;
}
.void-btn:active { color: var(--clay); border-color: var(--clay); }

/* ── Void form (inline) ─────────────────────────── */
.void-form {
    display: none;
    padding: 10px 14px 14px;
    border-top: 1px solid var(--border);
    background: #FDF8F5;
    gap: 10px;
    flex-direction: column;
}
.void-form.open {
    display: flex;
}
.void-reason-input {
    width: 100%;
    height: 42px;
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 0 12px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px;
    color: var(--espresso);
    background: #fff;
    outline: none;
    transition: border-color 0.15s;
}
.void-reason-input:focus { border-color: var(--espresso); }
.void-reason-input::placeholder { color: var(--border); }
.void-confirm-btn {
    height: 42px;
    background: var(--clay);
    color: #fff;
    border: none;
    border-radius: 9px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: opacity 0.13s;
}
.void-confirm-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.void-confirm-btn:not(:disabled):active { opacity: 0.8; }
.void-cancel-link {
    font-size: 12px;
    color: var(--muted);
    text-align: center;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
}
</style>
@endsection

@section('content')
<div class="myshift-wrap">

@if(session('success'))
<div style="margin:14px 16px; padding:11px 15px; background:#DFF0DD; color:var(--forest); border-radius:10px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px;">
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7l3.5 3.5 5.5-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
    {{ session('success') }}
</div>
@endif

@if(!$shift)

{{-- ── No open shift ───────────────────────────── --}}
<div class="no-shift-state">
    <p class="no-shift-title">No shift open</p>
    <p class="no-shift-sub">Open a shift to start selling.</p>
    <a href="{{ route('sales.index') }}" class="open-shift-link">Open Shift →</a>
</div>

@else

{{-- ── Open shift ──────────────────────────────── --}}
@php
    $dur      = $shift->opened_at->diff(now());
    $durStr   = $dur->h > 0
        ? $dur->h . 'h ' . $dur->i . 'm'
        : $dur->i . 'm';
    $since    = $shift->opened_at->format('g:ia');
@endphp

{{-- Header --}}
<div class="shift-header">
    <p class="shift-header-name">{{ session('auth_name') }}</p>
    <p class="shift-header-since">Shift open since {{ $since }} · {{ $durStr }}</p>
</div>

{{-- Running totals --}}
<div class="totals-grid">
    <div class="total-box">
        <span class="total-box-label">Total</span>
        <span class="total-box-val" id="live-total">{{ number_format((int)$totalSales) }}</span>
    </div>
    <div class="total-box">
        <span class="total-box-label">Cash</span>
        <span class="total-box-val" id="live-cash">{{ number_format((int)$cashTotal) }}</span>
    </div>
    <div class="total-box">
        <span class="total-box-label">M-Pesa</span>
        <span class="total-box-val" id="live-mpesa">{{ number_format((int)$mpesaTotal) }}</span>
    </div>
</div>

{{-- Sales list --}}
<p class="sales-section-title">Sales this shift</p>

<div class="sales-list" id="sales-list">

@if($allSales->isEmpty())
    <p class="sales-empty" id="empty-state">No sales yet this shift.<br>Tap a product to make your first sale.</p>
@else
    <p class="sales-empty" id="empty-state" style="display:none;">No sales yet this shift.<br>Tap a product to make your first sale.</p>

    @foreach($allSales as $sale)
    @php
        $isVoided = (bool) $sale->voided_at;
        $prodName = $sale->product?->name ?? '—';
        $variant  = $sale->variant?->size;
        $qty      = (float) $sale->quantity_or_ml;
        $qtyStr   = $sale->product?->type === 'measured'
            ? number_format($qty, 0) . 'ml'
            : ($qty == 1 ? '1' : number_format($qty, 0));
    @endphp
    <div class="sale-item {{ $isVoided ? 'voided' : '' }}" id="sale-{{ $sale->id }}">

        <div class="sale-item-main">
            <div class="sale-item-left">
                <p class="sale-item-name">{{ $prodName }}{{ $variant ? ' · ' . $variant : '' }}</p>
                <p class="sale-item-meta">{{ $qtyStr }} · {{ tenant('currency_symbol') }} {{ number_format((int)$sale->actual_price) }}</p>
                @if($isVoided && $sale->void_reason)
                <p class="sale-item-void-reason">Void: {{ $sale->void_reason }}</p>
                @endif
            </div>
            <div class="sale-item-right">
                <span class="sale-item-amount">{{ tenant('currency_symbol') }} {{ number_format((int)$sale->total) }}</span>
                <span class="sale-item-time">{{ $sale->created_at->format('g:ia') }}</span>
                @if($isVoided)
                    <span class="voided-badge">VOIDED</span>
                @else
                    <span class="pay-badge pay-{{ $sale->payment_type }}">{{ strtoupper($sale->payment_type) }}</span>
                    <button class="void-btn"
                            onclick="openVoidForm({{ $sale->id }})"
                            id="void-btn-{{ $sale->id }}">
                        Void
                    </button>
                    <a href="{{ route('exchange.create', $sale) }}"
                       class="exchange-btn">
                        Exchange
                    </a>
                @endif
            </div>
        </div>

        @if(!$isVoided)
        <div class="void-form" id="void-form-{{ $sale->id }}">
            <input
                type="text"
                class="void-reason-input"
                id="void-reason-{{ $sale->id }}"
                placeholder="Reason for void…"
                maxlength="255"
                oninput="onReasonInput({{ $sale->id }})"
            >
            <button
                class="void-confirm-btn"
                id="void-confirm-{{ $sale->id }}"
                onclick="confirmVoid({{ $sale->id }}, {{ (int)$sale->total }}, '{{ $sale->payment_type }}')"
                disabled>
                Confirm void
            </button>
            <span class="void-cancel-link" onclick="closeVoidForm({{ $sale->id }})">Cancel</span>
        </div>
        @endif

    </div>
    @endforeach
@endif

</div>

@endif

</div>
@endsection

@section('scripts')
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Running totals (updated live on void)
var liveTotal = {{ (int)($totalSales ?? 0) }};
var liveCash  = {{ (int)($cashTotal  ?? 0) }};
var liveMpesa = {{ (int)($mpesaTotal ?? 0) }};

function fmtNum(n) {
    return Math.round(n).toLocaleString('en-KE');
}

function updateTotals() {
    var t = document.getElementById('live-total');
    var c = document.getElementById('live-cash');
    var m = document.getElementById('live-mpesa');
    if (t) t.textContent = fmtNum(liveTotal);
    if (c) c.textContent = fmtNum(liveCash);
    if (m) m.textContent = fmtNum(liveMpesa);
}

function openVoidForm(id) {
    // Close any other open forms
    document.querySelectorAll('.void-form.open').forEach(function(f) {
        f.classList.remove('open');
    });
    var form = document.getElementById('void-form-' + id);
    if (form) {
        form.classList.add('open');
        var inp = document.getElementById('void-reason-' + id);
        if (inp) { inp.value = ''; inp.focus(); }
        var btn = document.getElementById('void-confirm-' + id);
        if (btn) btn.disabled = true;
    }
}

function closeVoidForm(id) {
    var form = document.getElementById('void-form-' + id);
    if (form) form.classList.remove('open');
}

function onReasonInput(id) {
    var inp = document.getElementById('void-reason-' + id);
    var btn = document.getElementById('void-confirm-' + id);
    if (inp && btn) {
        btn.disabled = inp.value.trim().length === 0;
    }
}

function confirmVoid(id, amount, payType) {
    var btn    = document.getElementById('void-confirm-' + id);
    var reason = document.getElementById('void-reason-' + id).value.trim();

    if (!reason) return;

    btn.disabled    = true;
    btn.textContent = 'Voiding…';

    fetch('/sales/' + id + '/void', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':  csrfToken,
            'Accept':        'application/json',
        },
        body: JSON.stringify({ void_reason: reason }),
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            // Update the row visually
            var row = document.getElementById('sale-' + id);
            row.classList.add('voided');

            var nameEl = row.querySelector('.sale-item-name');
            if (nameEl) nameEl.style.textDecoration = 'line-through';

            // Replace pay badge + void button with VOIDED badge
            var rightEl = row.querySelector('.sale-item-right');
            var payBadge = rightEl.querySelector('.pay-badge');
            var voidBtn  = rightEl.querySelector('.void-btn');
            if (payBadge) payBadge.remove();
            if (voidBtn)  voidBtn.remove();

            var vBadge = document.createElement('span');
            vBadge.className = 'voided-badge';
            vBadge.textContent = 'VOIDED';
            rightEl.appendChild(vBadge);

            // Add void reason to left side
            var leftEl = row.querySelector('.sale-item-left');
            var reasonP = document.createElement('p');
            reasonP.className = 'sale-item-void-reason';
            reasonP.textContent = 'Void: ' + reason;
            leftEl.appendChild(reasonP);

            // Hide void form
            var form = document.getElementById('void-form-' + id);
            if (form) form.remove();

            // Dim the row
            var mainEl = row.querySelector('.sale-item-main');
            if (mainEl) mainEl.style.opacity = '0.45';

            // Update running totals
            liveTotal -= amount;
            if (payType === 'cash')  liveCash  -= amount;
            if (payType === 'mpesa') liveMpesa -= amount;
            updateTotals();

        } else {
            btn.disabled    = false;
            btn.textContent = 'Confirm void';
            alert(data.error || 'Could not void this sale.');
        }
    })
    .catch(function() {
        btn.disabled    = false;
        btn.textContent = 'Confirm void';
        alert('Network error. Please try again.');
    });
}
</script>
@endsection
