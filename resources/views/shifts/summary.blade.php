@extends('layouts.staff')

@section('title', 'Shift Closed')

@section('styles')
<style>
/* ── Page wrap ──────────────────────────────────── */
.summary-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 24px 16px 48px;
    min-height: calc(100dvh - var(--top-h) - var(--tab-h));
}

/* ── Result card ────────────────────────────────── */
.summary-card {
    width: 100%;
    max-width: 420px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 28px 24px;
    display: flex;
    flex-direction: column;
    gap: 22px;
}

/* ── Header ─────────────────────────────────────── */
.summary-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 24px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.1;
}
.summary-times {
    font-size: 13px;
    color: var(--muted);
    margin-top: 6px;
    line-height: 1.5;
}

/* ── Big total ──────────────────────────────────── */
.summary-total {
    font-family: "DM Mono", monospace;
    font-size: 40px;
    font-weight: 700;
    color: var(--espresso);
    letter-spacing: -0.01em;
    line-height: 1;
}
.summary-total-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
    margin-bottom: 6px;
}

/* ── Breakdown rows ─────────────────────────────── */
.breakdown {
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
}
.bd-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    padding: 10px 14px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
}
.bd-row:last-child { border-bottom: none; }
.bd-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
}
.bd-value {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    color: var(--espresso);
}

/* ── Result pill ────────────────────────────────── */
.summary-result {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 13px 16px;
    border-radius: var(--radius-md);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 15px;
    font-weight: 700;
    text-align: center;
}
.result-balanced {
    background: #DFF0DD;
    color: var(--forest);
}
.result-short {
    background: #F5E0D8;
    color: var(--clay);
}
.result-over {
    background: #DFF0DD;
    color: var(--forest);
}

/* ── Divider ────────────────────────────────────── */
.summary-divider {
    height: 1px;
    background: var(--border);
}

/* ── WhatsApp button ────────────────────────────── */
.wa-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    height: 56px;
    background: #4A6741;
    color: #fff;
    border: none;
    border-radius: var(--radius-default);
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.01em;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: opacity 0.13s;
}
.wa-btn:active { opacity: 0.82; }

/* ── Done link ──────────────────────────────────── */
.done-link {
    display: block;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    color: var(--muted);
    text-decoration: none;
    padding: 6px 0 2px;
    -webkit-tap-highlight-color: transparent;
}
.done-link:active { color: var(--espresso); }
</style>
@endsection

@section('content')
<div class="summary-wrap">
<div class="summary-card">

    {{-- ── Header ───────────────────────────────── --}}
    <div>
        <div class="summary-title">Shift closed</div>
        <div class="summary-times">
            Opened {{ $openedAt }} &middot; Closed {{ $closedAt }} &middot; {{ $duration }}
        </div>
    </div>

    {{-- ── Total sales ────────────────────────────── --}}
    <div>
        <div class="summary-total-label">Total sales</div>
        <div class="summary-total">{{ tenant('currency_symbol') }} {{ number_format((int) $totalSales) }}</div>
    </div>

    {{-- ── Breakdown ──────────────────────────────── --}}
    <div class="breakdown">
        <div class="bd-row">
            <span class="bd-label">Cash</span>
            <span class="bd-value">{{ tenant('currency_symbol') }} {{ number_format((int) $cashSales) }}</span>
        </div>
        <div class="bd-row">
            <span class="bd-label">M-Pesa</span>
            <span class="bd-value">{{ tenant('currency_symbol') }} {{ number_format((int) $mpesaSales) }}</span>
        </div>
        @if($creditSales > 0)
        <div class="bd-row">
            <span class="bd-label">Credit</span>
            <span class="bd-value">{{ tenant('currency_symbol') }} {{ number_format((int) $creditSales) }}</span>
        </div>
        @endif
        <div class="bd-row">
            <span class="bd-label">{{ $saleCount }} {{ $saleCount === 1 ? 'sale' : 'sales' }}</span>
            <span class="bd-value"></span>
        </div>
    </div>

    {{-- ── Result ──────────────────────────────────── --}}
    @if($discrepancy == 0)
        <div class="summary-result result-balanced">
            Till balanced ✓
        </div>
    @elseif($discrepancy < 0)
        <div class="summary-result result-short">
            {{ tenant('currency_symbol') }} {{ number_format((int) abs($discrepancy)) }} short
        </div>
    @else
        <div class="summary-result result-over">
            {{ tenant('currency_symbol') }} {{ number_format((int) $discrepancy) }} over ↑
        </div>
    @endif

    <div class="summary-divider"></div>

    {{-- ── WhatsApp share button ──────────────────── --}}
    @if($waUrl)
        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="wa-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Send report to owner
        </a>
    @endif

    {{-- ── Done ────────────────────────────────────── --}}
    <a href="{{ route('sales.index') }}" class="done-link">Done</a>

</div>
</div>
@endsection
