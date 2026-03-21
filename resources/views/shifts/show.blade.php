@extends('layouts.app')

@section('title', 'Shift — ' . $shift->staff->name)

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">{{ $shift->staff->name }}'s shift</h1>
        <p class="page-subtitle">
            {{ $shift->opened_at->format('D d M Y') }} ·
            @if($shift->status === 'open')
                <span style="color:var(--forest);font-weight:600;">Open now</span>
            @else
                Closed {{ $shift->closed_at->format('g:ia') }}
            @endif
        </p>
    </div>
    <a href="{{ route('shifts.index') }}" class="btn btn-secondary">← All shifts</a>
</div>
@endsection

@section('styles')
<style>
/* ── Summary cards ──────────────────────────────── */
.shift-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}
@media (max-width: 900px) {
    .shift-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .shift-stats { grid-template-columns: 1fr 1fr; gap: 10px; }
}
.ss-box {
    background: #fff; border: 1px solid var(--border);
    border-radius: 14px; padding: 18px 20px;
    box-shadow: 0 1px 3px rgba(28,24,20,0.05);
}
.ss-label {
    font-size: 11px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.08em;
    display: block; margin-bottom: 8px;
}
.ss-val {
    font-family: "DM Mono", monospace;
    font-size: 26px; font-weight: 500; color: var(--espresso);
    line-height: 1;
}
.ss-val.forest { color: var(--forest); }
.ss-val.clay   { color: var(--clay); }
.ss-sub { font-size: 12px; color: var(--muted); margin-top: 6px; }

/* ── Reconciliation block ───────────────────────── */
.recon-block {
    background: #fff; border: 1px solid var(--border);
    border-radius: 14px; padding: 20px 22px;
    margin-bottom: 28px;
    display: flex; gap: 40px; flex-wrap: wrap;
    align-items: flex-start;
}
.recon-section-title {
    font-size: 12px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.08em;
    margin-bottom: 14px; display: block;
    flex-basis: 100%;
}
.recon-col {
    display: flex; flex-direction: column; gap: 10px;
    flex: 1; min-width: 160px;
}
.recon-row {
    display: flex; justify-content: space-between;
    align-items: baseline; gap: 16px;
}
.recon-label {
    font-size: 12px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.06em;
    white-space: nowrap;
}
.recon-val {
    font-family: "DM Mono", monospace;
    font-size: 14px; color: var(--espresso);
}
.recon-divider {
    height: 1px; background: var(--border); flex-basis: 100%;
}
.recon-result-big {
    font-family: "DM Mono", monospace;
    font-size: 22px; font-weight: 700;
}
.result-balanced { color: var(--forest); }
.result-short    { color: var(--clay); }
.result-over     { color: var(--forest); }

/* ── Sales table ────────────────────────────────── */
.section-head {
    font-size: 12px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.08em;
    margin-bottom: 12px; display: block;
}
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    font-size: 11px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.07em;
    padding: 11px 18px; text-align: left;
    border-bottom: 1px solid var(--border); white-space: nowrap;
}
.data-table td {
    padding: 12px 18px; font-size: 13.5px; color: var(--espresso);
    border-bottom: 1px solid var(--border); vertical-align: middle;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tr.voided td { opacity: 0.45; }
.td-mono   { font-family: "DM Mono", monospace; }
.td-muted  { color: var(--muted); font-size: 12.5px; }
.td-strike { text-decoration: line-through; }

/* ── Empty state ────────────────────────────────── */
.empty-sales {
    text-align: center; padding: 40px; color: var(--muted); font-size: 14px;
}

/* ── Mobile sale cards ──────────────────────────── */
.desktop-sales { display: block; }
.mobile-sales  { display: none; }
@media (max-width: 767px) {
    .desktop-sales { display: none; }
    .mobile-sales  { display: block; }
}
.sale-card-m {
    background: #fff; border-bottom: 1px solid var(--border); padding: 12px 16px;
}
.sale-card-m:first-child { border-radius: 14px 14px 0 0; }
.sale-card-m:last-child  { border-bottom: none; border-radius: 0 0 14px 14px; }
.sale-card-m.voided { opacity: 0.45; }
.scm-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.scm-name { font-size: 14px; font-weight: 500; }
.scm-name.strike { text-decoration: line-through; }
.scm-meta { font-size: 12px; color: var(--muted); margin-top: 2px; font-family: "DM Mono", monospace; }
.scm-amt  { font-family: "DM Mono", monospace; font-size: 14px; font-weight: 500; flex-shrink: 0; }
</style>
@endsection

@section('content')

{{-- ── Summary stats ──────────────────────────── --}}
<div class="shift-stats">
    <div class="ss-box">
        <span class="ss-label">Total sales</span>
        <span class="ss-val">{{ tenant('currency_symbol') }} {{ number_format((int)$totalSales) }}</span>
        <p class="ss-sub">{{ $saleCount }} {{ $saleCount === 1 ? 'transaction' : 'transactions' }}</p>
    </div>
    <div class="ss-box">
        <span class="ss-label">Cash</span>
        <span class="ss-val forest">{{ tenant('currency_symbol') }} {{ number_format((int)$cashSales) }}</span>
    </div>
    <div class="ss-box">
        <span class="ss-label">M-Pesa</span>
        <span class="ss-val forest">{{ tenant('currency_symbol') }} {{ number_format((int)$mpesaSales) }}</span>
    </div>
    @if($creditSales > 0)
    <div class="ss-box">
        <span class="ss-label">Deposit</span>
        <span class="ss-val clay">{{ tenant('currency_symbol') }} {{ number_format((int)$creditSales) }}</span>
    </div>
    @endif
</div>

{{-- ── Reconciliation (closed shifts only) ─────── --}}
@if($shift->status === 'closed')
<div class="recon-block">
    <span class="recon-section-title">Till reconciliation</span>

    <div class="recon-col">
        <div class="recon-row">
            <span class="recon-label">Opening counter float</span>
            <span class="recon-val">{{ tenant('currency_symbol') }} {{ number_format((int)$shift->opening_float) }}</span>
        </div>
        <div class="recon-row">
            <span class="recon-label">Cash sales</span>
            <span class="recon-val">{{ tenant('currency_symbol') }} {{ number_format((int)$cashSales) }}</span>
        </div>
        <div class="recon-row">
            <span class="recon-label">Expected in counter</span>
            <span class="recon-val" style="font-weight:600;">{{ tenant('currency_symbol') }} {{ number_format((int)$expectedCash) }}</span>
        </div>
    </div>

    <div class="recon-col">
        <div class="recon-row">
            <span class="recon-label">Cash counted</span>
            <span class="recon-val">{{ tenant('currency_symbol') }} {{ number_format((int)$shift->cash_counted) }}</span>
        </div>
        <div class="recon-row">
            <span class="recon-label">Discrepancy</span>
            @if($disc == 0)
                <span class="recon-result-big result-balanced">Balanced ✓</span>
            @elseif($disc < 0)
                <span class="recon-result-big result-short">{{ tenant('currency_symbol') }} {{ number_format(abs($disc), 0) }} short</span>
            @else
                <span class="recon-result-big result-over">{{ tenant('currency_symbol') }} {{ number_format($disc, 0) }} over</span>
            @endif
        </div>
    </div>
</div>
@elseif($shift->status === 'open')
<div style="margin-bottom:24px;padding:14px 18px;background:#DFF0DD;border-radius:12px;display:flex;align-items:center;gap:10px;">
    <span style="width:8px;height:8px;background:var(--forest);border-radius:50%;flex-shrink:0;"></span>
    <span style="font-size:14px;color:var(--forest);font-weight:600;">Shift is currently open</span>
</div>
@endif

{{-- ── Sales list ──────────────────────────────── --}}
<span class="section-head">All sales in this shift</span>

@if($allSales->isEmpty())
<div class="card empty-sales">No sales recorded in this shift.</div>
@else

{{-- Desktop --}}
<div class="card desktop-sales" style="overflow:hidden;">
<table class="data-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
    @foreach($allSales as $sale)
    @php
        $isVoided  = (bool)$sale->voided_at;
        $prodName  = $sale->product?->name ?? '—';
        $variant   = $sale->variant?->size;
        $qty       = (float)$sale->quantity_or_ml;
        $qtyStr    = $sale->product?->type === 'measured'
            ? number_format($qty, 0) . 'ml'
            : number_format($qty, 0);
    @endphp
    <tr class="{{ $isVoided ? 'voided' : '' }}">
        <td>
            <span class="{{ $isVoided ? 'td-strike' : '' }}" style="font-weight:500;">
                {{ $prodName }}{{ $variant ? ' · ' . $variant : '' }}
            </span>
            @if($isVoided && $sale->void_reason)
            <span class="td-muted" style="display:block;color:var(--clay);font-size:11.5px;font-style:italic;">
                Void: {{ $sale->void_reason }}
            </span>
            @endif
        </td>
        <td class="td-mono">{{ $qtyStr }}</td>
        <td class="td-mono">{{ number_format((int)$sale->actual_price) }}</td>
        <td class="td-mono" style="font-weight:500;">{{ number_format((int)$sale->total) }}</td>
        <td>
            @if($isVoided)
                <span class="badge badge-tan">VOIDED</span>
            @elseif($sale->payment_type === 'cash')
                <span class="badge badge-green">CASH</span>
            @elseif($sale->payment_type === 'mpesa')
                <span class="badge badge-green">M-PESA</span>
            @else
                <span class="badge badge-clay">CREDIT</span>
            @endif
        </td>
        <td class="td-muted">{{ $sale->created_at->format('g:ia') }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>

{{-- Mobile --}}
<div class="card mobile-sales" style="overflow:hidden;">
@foreach($allSales as $sale)
@php
    $isVoided = (bool)$sale->voided_at;
    $prodName = $sale->product?->name ?? '—';
    $variant  = $sale->variant?->size;
    $qty      = (float)$sale->quantity_or_ml;
    $qtyStr   = $sale->product?->type === 'measured'
        ? number_format($qty, 0) . 'ml'
        : number_format($qty, 0);
@endphp
<div class="sale-card-m {{ $isVoided ? 'voided' : '' }}">
    <div class="scm-row">
        <div>
            <p class="scm-name {{ $isVoided ? 'strike' : '' }}">
                {{ $prodName }}{{ $variant ? ' · ' . $variant : '' }}
            </p>
            <p class="scm-meta">
                {{ $qtyStr }} · {{ tenant('currency_symbol') }} {{ number_format((int)$sale->actual_price) }} ·
                @if($isVoided) VOIDED
                @else {{ strtoupper($sale->payment_type) }}
                @endif
                · {{ $sale->created_at->format('g:ia') }}
            </p>
            @if($isVoided && $sale->void_reason)
            <p style="font-size:11px;color:var(--clay);font-style:italic;margin-top:2px;">Void: {{ $sale->void_reason }}</p>
            @endif
        </div>
        <span class="scm-amt">{{ tenant('currency_symbol') }} {{ number_format((int)$sale->total) }}</span>
    </div>
</div>
@endforeach
</div>

@endif

@endsection
