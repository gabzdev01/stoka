@extends('layouts.staff')
@section('no-search', true)

@section('title', 'Close Shift')

@section('styles')
<style>
/* ── Wrap ───────────────────────────────────────── */
.close-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 16px 40px;
    min-height: calc(100dvh - var(--top-h) - var(--tab-h) - 20px);
}

/* ── State A: no open shift ─────────────────────── */
.no-shift-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-align: center;
    flex: 1;
    padding: 60px 0;
}
.no-shift-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 26px;
    font-weight: 600;
    color: var(--espresso);
}
.no-shift-sub {
    font-size: 14px;
    color: var(--muted);
}
.go-link {
    margin-top: 8px;
    font-size: 14px;
    font-weight: 600;
    color: var(--terracotta);
}

/* ── Card ───────────────────────────────────────── */
.close-card {
    width: 100%;
    max-width: 420px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 28px 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ── Card header ────────────────────────────────── */
.close-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 24px;
    font-weight: 600;
    color: var(--espresso);
    line-height: 1.1;
}
.close-sub {
    font-size: 13px;
    color: var(--muted);
    margin-top: 5px;
}

/* ── Shift summary ──────────────────────────────── */
.shift-summary {
    display: flex;
    flex-direction: column;
    gap: 0;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border);
}
.ss-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    padding: 10px 14px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
}
.ss-row:last-child { border-bottom: none; }
.ss-row-expected {
    background: #fff;
}
.ss-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
}
.ss-value {
    font-family: "DM Mono", monospace;
    font-size: 15px;
    font-weight: 500;
    color: var(--terracotta);
    text-align: right;
}
.ss-row-expected .ss-value {
    color: var(--espresso);
    font-size: 16px;
}
.ss-count {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 11px;
    color: var(--muted);
    margin-left: 6px;
}

/* ── Counter input ─────────────────────────────────── */
.counter-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 10px;
}
.counter-input-wrap {
    display: flex;
    align-items: center;
    background: var(--bg);
    border: 2px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.15s;
}
.counter-input-wrap:focus-within {
    border-color: var(--espresso);
}
.counter-prefix {
    padding: 0 14px 0 16px;
    font-family: "DM Mono", monospace;
    font-size: 15px;
    font-weight: 500;
    color: var(--muted);
    white-space: nowrap;
    border-right: 1px solid var(--border);
    height: 56px;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    background: var(--surface);
}
.counter-input {
    flex: 1;
    height: 56px;
    border: none;
    background: transparent;
    font-family: "DM Mono", monospace;
    font-size: 22px;
    font-weight: 500;
    color: var(--espresso);
    padding: 0 16px;
    outline: none;
    min-width: 0;
    -webkit-appearance: none;
    appearance: none;
}
.counter-input::placeholder { color: var(--border); }
.counter-input::-webkit-outer-spin-button,
.counter-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.counter-input[type=number] { -moz-appearance: textfield; }

/* ── Live reconciliation ────────────────────────── */
.recon-section {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.recon-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
}
.recon-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
}
.recon-val {
    font-family: "DM Mono", monospace;
    font-size: 15px;
    color: var(--espresso);
}
.recon-divider {
    height: 1px;
    background: var(--border);
    margin: 4px 0;
}
.recon-result {
    font-family: "DM Mono", monospace;
    font-size: 26px;
    font-weight: 700;
    text-align: center;
    padding: 10px 0 4px;
    line-height: 1;
}
.recon-result.balanced { color: var(--forest); }
.recon-result.over     { color: var(--forest); }
.recon-result.short    { color: var(--clay); }
.recon-note {
    font-size: 12px;
    color: var(--muted);
    text-align: center;
    line-height: 1.4;
}

/* ── Owner close (no till count) ───────────────── */
.owner-close-note {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.6;
    text-align: center;
    padding: 4px 0;
}
.owner-totals {
    display: flex;
    flex-direction: column;
    gap: 0;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border);
}
.ot-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 11px 14px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
}
.ot-row:last-child { border-bottom: none; }
.ot-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
}
.ot-value {
    font-family: "DM Mono", monospace;
    font-size: 15px;
    color: var(--espresso);
}
.ot-value.ot-total {
    font-size: 17px;
    font-weight: 600;
    color: var(--forest);
}

/* ── Submit button ──────────────────────────────── */
.close-btn {
    width: 100%;
    height: 56px;
    background: var(--espresso);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.02em;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.13s, background 0.15s, color 0.15s;
    -webkit-tap-highlight-color: transparent;
}
.close-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.close-btn:not(:disabled):active { opacity: 0.82; }
.close-btn.btn-short    { background: var(--clay); }
.close-btn.btn-balanced { background: var(--forest); }
.close-btn.btn-over     { background: var(--espresso); }
</style>
@endsection

@section('content')
<div class="close-wrap">

@if(!$shift)

{{-- ── State A: no open shift ─────────────────── --}}
<div class="no-shift-state">
    <p class="no-shift-title">No shift to close</p>
    <p class="no-shift-sub">You don't have an open shift right now.</p>
    <a href="/sales" class="go-link">Go to Products →</a>
</div>

@else

{{-- ── State B: open shift ─────────────────────── --}}
@php
    $dur     = $shift->opened_at->diff(now());
    $durStr  = $dur->h > 0
        ? $dur->h . 'h ' . $dur->i . 'm'
        : $dur->i . 'm';
    $openedAt = $shift->opened_at->format('g:ia');
@endphp

<form method="POST" action="{{ route('shifts.close') }}" id="close-form">
@csrf

<div class="close-card">

    {{-- Header --}}
    <div>
        <h1 class="close-title">Close your shift</h1>
        <p class="close-sub">Opened at {{ $openedAt }} &middot; {{ $durStr }}</p>
    </div>

    {{-- Shift summary --}}
    <div class="shift-summary">
        <div class="ss-row">
            <span class="ss-label">Total sales</span>
            <span class="ss-value">
                {{ tenant('currency_symbol') }} {{ number_format((int)$totalSales) }}<span class="ss-count">{{ $saleCount }} {{ $saleCount === 1 ? 'transaction' : 'transactions' }}</span>
            </span>
        </div>
        <div class="ss-row">
            <span class="ss-label">M-Pesa received</span>
            <span class="ss-value">{{ tenant('currency_symbol') }} {{ number_format((int)$mpesaSales) }}</span>
        </div>
        <div class="ss-row">
            <span class="ss-label">Cash sales</span>
            <span class="ss-value">{{ tenant('currency_symbol') }} {{ number_format((int)$cashSales) }}</span>
        </div>
        <div class="ss-row">
            <span class="ss-label">Opening counter float</span>
            <span class="ss-value">{{ tenant('currency_symbol') }} {{ number_format((int)$shift->opening_float) }}</span>
        </div>
        <div class="ss-row ss-row-expected">
            <span class="ss-label">Expected in counter</span>
            <span class="ss-value">{{ tenant('currency_symbol') }} {{ number_format((int)$expectedCash) }}</span>
        </div>
    </div>

    @if($isOwnerClose)
    {{-- Owner close: no till to count, just a summary --}}
    <div class="owner-totals">
        @if($cashSales > 0)
        <div class="ot-row">
            <span class="ot-label">Cash received</span>
            <span class="ot-value">{{ tenant('currency_symbol') }} {{ number_format((int)$cashSales) }}</span>
        </div>
        @endif
        @if($mpesaSales > 0)
        <div class="ot-row">
            <span class="ot-label">M-Pesa received</span>
            <span class="ot-value">{{ tenant('currency_symbol') }} {{ number_format((int)$mpesaSales) }}</span>
        </div>
        @endif
        <div class="ot-row">
            <span class="ot-label">Total</span>
            <span class="ot-value ot-total">{{ tenant('currency_symbol') }} {{ number_format((int)$totalSales) }}</span>
        </div>
    </div>
    <p class="owner-close-note">Your session will be recorded and visible in shift history.</p>
    <button type="submit" class="close-btn btn-balanced" id="close-btn">
        End Session
    </button>

    @else
    {{-- Staff close: count the till --}}
    <div>
        <label class="counter-label" for="cash-counted">Cash in the Counter</label>
        <div class="counter-input-wrap">
            <span class="counter-prefix">{{ tenant('currency_symbol') }}</span>
            <input
                type="number"
                name="cash_counted"
                id="cash-counted"
                class="counter-input"
                min="0"
                step="1"
                placeholder="0"
                inputmode="numeric"
                autocomplete="off"
                autofocus
            >
        </div>
    </div>

    {{-- Live reconciliation --}}
    <div class="recon-section" id="recon" style="display:none;">
        <div class="recon-row">
            <span class="recon-label">Expected</span>
            <span class="recon-val">{{ tenant('currency_symbol') }} {{ number_format((int)$expectedCash) }}</span>
        </div>
        <div class="recon-row">
            <span class="recon-label">You counted</span>
            <span class="recon-val" id="recon-counted">—</span>
        </div>
        <div class="recon-divider"></div>
        <div class="recon-result" id="recon-result"></div>
        <div class="recon-note" id="recon-note"></div>
    </div>

    {{-- Submit button --}}
    <button type="submit" class="close-btn" id="close-btn" disabled>
        Close Shift
    </button>
    @endif

</div>
</form>

@endif

</div>
@endsection

@section('scripts')
<script>
(function () {
    var expected = {{ $expectedCash ?? 0 }};
    var input    = document.getElementById('cash-counted');
    var recon    = document.getElementById('recon');
    var counted  = document.getElementById('recon-counted');
    var result   = document.getElementById('recon-result');
    var note     = document.getElementById('recon-note');
    var btn      = document.getElementById('close-btn');

    if (!input) return; // State A — no shift, nothing to wire up

    function fmt(n) {
        return '{{ tenant("currency_symbol") }} ' + Math.round(Math.abs(n)).toLocaleString('en-KE');
    }

    input.addEventListener('input', function () {
        var raw = input.value;

        if (raw === '' || raw === null) {
            recon.style.display = 'none';
            btn.disabled = true;
            btn.textContent = 'Close Shift';
            btn.className = 'close-btn';
            return;
        }

        var val  = parseFloat(raw);
        if (isNaN(val) || val < 0) return;

        var diff = Math.round((val - expected) * 100) / 100;

        // Show recon panel
        recon.style.display = 'flex';
        counted.textContent = fmt(val);

        // Result state
        result.className = 'recon-result';
        if (diff === 0) {
            result.textContent = '✓ Balanced';
            result.classList.add('balanced');
            note.textContent = '';
            btn.textContent = 'Close Shift ✓';
            btn.className = 'close-btn btn-balanced';
        } else if (diff < 0) {
            result.textContent = fmt(diff) + ' short';
            result.classList.add('short');
            note.textContent = 'This will be recorded and visible to the shop owner.';
            btn.textContent = 'Close Shift — ' + fmt(diff) + ' short';
            btn.className = 'close-btn btn-short';
        } else {
            result.textContent = fmt(diff) + ' over';
            result.classList.add('over');
            note.textContent = 'This will also be recorded.';
            btn.textContent = 'Close Shift — ' + fmt(diff) + ' over';
            btn.className = 'close-btn btn-over';
        }

        btn.disabled = false;
    });
})();
</script>
@endsection
