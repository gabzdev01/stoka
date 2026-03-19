@extends('layouts.staff')

@section('title', 'History')

@section('styles')
<style>
/* ── Wrap ───────────────────────────────────────── */
.history-wrap {
    padding: 16px 16px 40px;
}

/* ── Shift closed flash ─────────────────────────── */
.closed-flash {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #DFF0DD;
    color: var(--forest);
    border-radius: 12px;
    padding: 13px 16px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
}
.closed-flash.flash-short {
    background: #F5E0D8;
    color: var(--clay);
}

/* ── Section title ──────────────────────────────── */
.hist-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 16px;
}

/* ── Empty state ────────────────────────────────── */
.hist-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 8px;
    padding: 60px 20px;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
}
.hist-empty-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--espresso);
}

/* ── Shift cards ────────────────────────────────── */
.shift-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    margin-bottom: 12px;
    overflow: hidden;
}

/* ── Shift card header (tappable) ───────────────── */
.shift-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 16px;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    gap: 12px;
}
.shift-card-header:active { background: var(--surface-2); }

.shift-card-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
    min-width: 0;
}
.shift-date-line {
    font-size: 13px;
    font-weight: 600;
    color: var(--espresso);
}
.shift-dur-line {
    font-size: 12px;
    color: var(--muted);
}
.shift-totals-line {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--espresso);
    margin-top: 4px;
}
.shift-split-line {
    font-size: 12px;
    color: var(--muted);
    font-family: "DM Mono", monospace;
}

.shift-card-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
    flex-shrink: 0;
}
.disc-badge {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
}
.disc-balanced {
    background: #DFF0DD;
    color: var(--forest);
}
.disc-short {
    background: #F5E0D8;
    color: var(--clay);
}
.disc-over {
    background: #DFF0DD;
    color: var(--forest);
}
.expand-chevron {
    color: var(--muted);
    transition: transform 0.2s ease;
}
.shift-card.open .expand-chevron {
    transform: rotate(180deg);
}

/* ── Expanded sales list ────────────────────────── */
.shift-sales {
    display: none;
    border-top: 1px solid var(--border);
    padding: 0;
}
.shift-card.open .shift-sales {
    display: block;
}
.sale-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    padding: 11px 16px;
    border-bottom: 1px solid var(--border);
}
.sale-row:last-child { border-bottom: none; }
.sale-row.voided { opacity: 0.5; }

.sale-left {
    flex: 1;
    min-width: 0;
}
.sale-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--espresso);
    line-height: 1.3;
}
.sale-name.voided-text {
    text-decoration: line-through;
}
.sale-meta {
    font-family: "DM Mono", monospace;
    font-size: 12px;
    color: var(--muted);
    margin-top: 3px;
}
.sale-void-reason {
    font-size: 11px;
    color: var(--clay);
    margin-top: 2px;
    font-style: italic;
}

.sale-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
    flex-shrink: 0;
}
.sale-amount {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    font-weight: 500;
    color: var(--espresso);
}
.sale-time {
    font-size: 11px;
    color: var(--muted);
}
.pay-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 2px 7px;
    border-radius: 10px;
}
.pay-cash  { background: #E8F0E6; color: var(--forest); }
.pay-mpesa { background: #E8F0E6; color: var(--forest); }
.pay-credit{ background: #F5E0D8; color: var(--clay); }
.voided-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 2px 7px;
    border-radius: 10px;
    background: #F0EDED;
    color: #9A8A82;
}
</style>
@endsection

@section('content')
<div class="history-wrap">

{{-- Shift-closed flash (from close shift redirect) --}}
@if(session('shift_closed'))
@php
    $flashMsg   = session('shift_closed');
    $flashShort = str_contains($flashMsg, 'short');
@endphp
<div class="closed-flash {{ $flashShort ? 'flash-short' : '' }}">
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" style="flex-shrink:0">
        <circle cx="9" cy="9" r="7.5" stroke="currentColor" stroke-width="1.5"/>
        <path d="M5.5 9l2.5 2.5 4.5-4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    {{ $flashMsg }}
</div>
@endif

<h2 class="hist-title">My shifts</h2>

@if($shifts->isEmpty())
<div class="hist-empty">
    <p class="hist-empty-title">No closed shifts yet</p>
    <p>Your shift history will appear here once you close your first shift.</p>
</div>
@else

@foreach($shifts as $shift)
@php
    $allSales   = $shift->sales;
    $active     = $allSales->whereNull('voided_at');
    $total      = $active->sum('total');
    $cash       = $active->where('payment_type', 'cash')->sum('total');
    $mpesa      = (float) $shift->mpesa_total;
    $count      = $active->count();
    $disc       = (float) $shift->cash_discrepancy;

    $dur        = $shift->opened_at->diff($shift->closed_at);
    $durStr     = $dur->h > 0
        ? $dur->h . 'h ' . $dur->i . 'm'
        : $dur->i . 'm';

    $closedDate = $shift->closed_at->isToday()     ? 'Today, '     . $shift->closed_at->format('g:ia')
                : ($shift->closed_at->isYesterday() ? 'Yesterday, ' . $shift->closed_at->format('g:ia')
                : $shift->closed_at->format('d M, g:ia'));
@endphp
<div class="shift-card" data-id="{{ $shift->id }}">

    {{-- Tappable header --}}
    <div class="shift-card-header" onclick="toggleShift(this)">
        <div class="shift-card-left">
            <span class="shift-date-line">{{ $closedDate }}</span>
            <span class="shift-dur-line">{{ $durStr }} shift</span>
            <span class="shift-totals-line">
                {{ tenant('currency_symbol') }} {{ number_format((int)$total) }}
                <span style="color:var(--muted);font-size:11px;"> · {{ $count }} {{ $count === 1 ? 'sale' : 'sales' }}</span>
            </span>
            <span class="shift-split-line">
                Cash {{ number_format((int)$cash) }} &nbsp;·&nbsp; M-Pesa {{ number_format((int)$mpesa) }}
            </span>
        </div>
        <div class="shift-card-right">
            @if($disc == 0)
                <span class="disc-badge disc-balanced">Balanced ✓</span>
            @elseif($disc < 0)
                <span class="disc-badge disc-short">{{ tenant('currency_symbol') }} {{ number_format(abs($disc), 0) }} short</span>
            @else
                <span class="disc-badge disc-over">{{ tenant('currency_symbol') }} {{ number_format($disc, 0) }} over</span>
            @endif
            <svg class="expand-chevron" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    {{-- Expandable sales --}}
    <div class="shift-sales">
        @if($allSales->isEmpty())
            <p style="padding:16px;font-size:13px;color:var(--muted);text-align:center;">No sales in this shift.</p>
        @else
            @foreach($allSales as $sale)
            @php
                $isVoided  = (bool) $sale->voided_at;
                $prodName  = $sale->product?->name ?? '—';
                $variant   = $sale->variant?->size;
                $qty       = (float) $sale->quantity_or_ml;
                $qtyStr    = $sale->product?->type === 'measured'
                    ? number_format($qty, 0) . 'ml'
                    : ($qty == 1 ? '1' : number_format($qty, 0));
            @endphp
            <div class="sale-row {{ $isVoided ? 'voided' : '' }}">
                <div class="sale-left">
                    <p class="sale-name {{ $isVoided ? 'voided-text' : '' }}">
                        {{ $prodName }}{{ $variant ? ' · ' . $variant : '' }}
                    </p>
                    <p class="sale-meta">{{ $qtyStr }} · {{ tenant('currency_symbol') }} {{ number_format((int)$sale->actual_price) }}</p>
                    @if($isVoided && $sale->void_reason)
                    <p class="sale-void-reason">Void: {{ $sale->void_reason }}</p>
                    @endif
                </div>
                <div class="sale-right">
                    <span class="sale-amount {{ $isVoided ? '' : '' }}">{{ tenant('currency_symbol') }} {{ number_format((int)$sale->total) }}</span>
                    <span class="sale-time">{{ $sale->created_at->format('g:ia') }}</span>
                    @if($isVoided)
                        <span class="voided-badge">VOIDED</span>
                    @else
                        <span class="pay-badge pay-{{ $sale->payment_type }}">{{ strtoupper($sale->payment_type) }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        @endif
    </div>

</div>
@endforeach

@endif

</div>
@endsection

@section('scripts')
<script>
function toggleShift(header) {
    var card = header.closest('.shift-card');
    card.classList.toggle('open');
}
</script>
@endsection
