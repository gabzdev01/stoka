@extends('layouts.app')

@section('title', 'Shifts')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Shifts</h1>
        <p class="page-subtitle">
            @if($openCount > 0)
                <span style="color:var(--forest);font-weight:600;">{{ $openCount }} open</span> ·
            @endif
            {{ $closedCount }} closed
        </p>
    </div>
</div>
@endsection

@section('styles')
<style>
.desktop-table { display: block; }
.mobile-cards  { display: none; }
@media (max-width: 767px) {
    .desktop-table { display: none; }
    .mobile-cards  { display: block; }
}

/* ── Table ──────────────────────────────────────── */
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    font-size: 11px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.07em;
    padding: 12px 20px; text-align: left;
    border-bottom: 1px solid var(--border); white-space: nowrap;
}
.data-table td {
    padding: 14px 20px; font-size: 13.5px; color: var(--espresso);
    border-bottom: 1px solid var(--border); vertical-align: middle;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: #FAF5EF; }
.data-table a.row-link { display: contents; color: inherit; text-decoration: none; }
.td-staff { font-weight: 600; }
.td-mono  { font-family: "DM Mono", monospace; }
.td-muted { color: var(--muted); font-size: 12.5px; }

/* ── Status badge ───────────────────────────────── */
.shift-status-open {
    display: inline-flex; align-items: center; gap: 5px;
    background: #DFF0DD; color: var(--forest);
    padding: 2px 9px; border-radius: var(--radius-full);
    font-size: 11px; font-weight: 600;
}
.shift-status-open .dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--forest);
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.4); opacity: 0.6; }
}

/* ── Discrepancy badge ──────────────────────────── */
.disc-ok   { color: var(--forest); font-weight: 600; }
.disc-short{ color: var(--clay);   font-weight: 600; font-family: "DM Mono", monospace; }
.disc-over { color: var(--forest); font-weight: 600; font-family: "DM Mono", monospace; }

/* ── Mobile cards ───────────────────────────────── */
.shift-card-m {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius-default); padding: 16px; margin-bottom: 10px;
    display: block; text-decoration: none; color: inherit;
}
.shift-card-m:active { background: #EDE5D8; }
.scm-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.scm-staff { font-weight: 600; font-size: 15px; }
.scm-date  { font-size: 12px; color: var(--muted); }
.scm-totals{ font-family: "DM Mono", monospace; font-size: 13px; color: var(--espresso); }
.scm-split { font-size: 12px; color: var(--muted); margin-top: 3px; }
.scm-bot   { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }

/* ── Empty state ────────────────────────────────── */
.empty-state {
    text-align: center; padding: 60px 20px;
    color: var(--muted); font-size: 14px;
}
</style>
@endsection

@section('content')

@if($staffCount === 0)
<p style="font-size:13px; color:var(--muted); margin-bottom:20px;">
    No staff accounts yet.
    <a href="{{ route('settings.index') }}" style="color:var(--terracotta); font-weight:600; text-decoration:none;">Add a staff member →</a>
</p>
@endif

@if($shifts->isEmpty())
<div class="empty-state">No shifts recorded yet.</div>
@else

{{-- ── Desktop table ───────────────────────────── --}}
<div class="desktop-table card" style="overflow:hidden;">
<table class="data-table">
    <thead>
        <tr>
            <th>Staff</th>
            <th>Date</th>
            <th>Duration</th>
            <th>Sales</th>
            <th>Cash</th>
            <th>M-Pesa</th>
            <th>Result</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($shifts as $shift)
    @php
        $isOpen     = $shift->status === 'open';
        $openedDate = $shift->opened_at->isToday()     ? 'Today'
                    : ($shift->opened_at->isYesterday() ? 'Yesterday'
                    : $shift->opened_at->format('d M Y'));
        $openedTime = $shift->opened_at->format('g:ia');

        if ($isOpen) {
            $dur = $shift->opened_at->diff(now());
        } else {
            $dur = $shift->opened_at->diff($shift->closed_at);
        }
        $durStr = $dur->h > 0 ? $dur->h . 'h ' . $dur->i . 'm' : $dur->i . 'm';

        $total   = $isOpen ? (float)($shift->_sale_total ?? 0) : (float)$shift->activeSales->sum('total');
        $cash    = $isOpen ? 0 : (float)$shift->activeSales->where('payment_type','cash')->sum('total');
        $mpesa   = (float) $shift->mpesa_total;
        $disc    = (float) $shift->cash_discrepancy;
        $count   = $isOpen ? (int)($shift->_sale_count ?? 0) : $shift->activeSales->count();
    @endphp
    <tr style="cursor:pointer;" onclick="window.location='/shifts/{{ $shift->id }}'">
        <td class="td-staff">{{ $shift->staff->name }}</td>
        <td>
            <span>{{ $openedDate }}</span>
            <span class="td-muted" style="display:block;">{{ $openedTime }}</span>
        </td>
        <td class="td-muted">{{ $durStr }}</td>
        <td class="td-mono">
            {{ number_format((int)$total) }}
            <span class="td-muted" style="display:block;">{{ $count }} {{ $count === 1 ? 'sale' : 'sales' }}</span>
        </td>
        <td class="td-mono">{{ number_format((int)$cash) }}</td>
        <td class="td-mono">{{ number_format((int)$mpesa) }}</td>
        <td>
            @if($isOpen)
                <span class="td-muted">—</span>
            @elseif($disc == 0)
                <span class="disc-ok">Balanced ✓</span>
            @elseif($disc < 0)
                <span class="disc-short">{{ tenant('currency_symbol') }} {{ number_format(abs($disc), 0) }} short</span>
            @else
                <span class="disc-over">{{ tenant('currency_symbol') }} {{ number_format($disc, 0) }} over</span>
            @endif
        </td>
        <td>
            @if($isOpen)
                <span class="shift-status-open"><span class="dot"></span>Open</span>
            @else
                <span class="badge badge-tan">Closed</span>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>

{{-- ── Mobile cards ────────────────────────────── --}}
<div class="mobile-cards">
@foreach($shifts as $shift)
@php
    $isOpen     = $shift->status === 'open';
    $openedDate = $shift->opened_at->isToday()     ? 'Today, '
                : ($shift->opened_at->isYesterday() ? 'Yesterday, '
                : $shift->opened_at->format('d M, '));
    if ($isOpen) {
        $dur = $shift->opened_at->diff(now());
    } else {
        $dur = $shift->opened_at->diff($shift->closed_at);
    }
    $durStr = $dur->h > 0 ? $dur->h . 'h ' . $dur->i . 'm' : $dur->i . 'm';
    $total  = $isOpen ? (float)($shift->_sale_total ?? 0) : (float)$shift->activeSales->sum('total');
    $cash   = $isOpen ? 0 : (float)$shift->activeSales->where('payment_type','cash')->sum('total');
    $mpesa  = (float) $shift->mpesa_total;
    $disc   = (float) $shift->cash_discrepancy;
    $count  = $isOpen ? (int)($shift->_sale_count ?? 0) : $shift->activeSales->count();
@endphp
<a href="/shifts/{{ $shift->id }}" class="shift-card-m">
    <div class="scm-top">
        <div>
            <p class="scm-staff">{{ $shift->staff->name }}</p>
            <p class="scm-date">{{ $openedDate }}{{ $shift->opened_at->format('g:ia') }} · {{ $durStr }}</p>
        </div>
        @if($isOpen)
            <span class="shift-status-open"><span class="dot"></span>Open</span>
        @else
            <span class="badge badge-tan">Closed</span>
        @endif
    </div>
    <p class="scm-totals">{{ tenant('currency_symbol') }} {{ number_format((int)$total) }} · {{ $count }} {{ $count === 1 ? 'sale' : 'sales' }}</p>
    <p class="scm-split">Cash {{ number_format((int)$cash) }} · M-Pesa {{ number_format((int)$mpesa) }}</p>
    <div class="scm-bot">
        <span style="font-size:12px;color:var(--muted);">Tap to view</span>
        @if(!$isOpen)
            @if($disc == 0)
                <span class="disc-ok" style="font-size:13px;">Balanced ✓</span>
            @elseif($disc < 0)
                <span class="disc-short" style="font-size:13px;">{{ tenant('currency_symbol') }} {{ number_format(abs($disc), 0) }} short</span>
            @else
                <span class="disc-over" style="font-size:13px;">{{ tenant('currency_symbol') }} {{ number_format($disc, 0) }} over</span>
            @endif
        @endif
    </div>
</a>
@endforeach
</div>

@endif

@endsection
