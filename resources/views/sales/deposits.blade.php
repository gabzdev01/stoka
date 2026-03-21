@extends('layouts.staff')
@section('no-search', true)
@section('title', 'Deposits')

@section('styles')
<style>
.dep-wrap {
    max-width: 520px;
    margin: 0 auto;
}

/* ── Page heading ──────────────────────────────── */
.dep-heading {
    font-family: "Cormorant Garamond", serif;
    font-size: 26px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 4px;
}
.dep-subheading {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 20px;
}

/* ── Summary strip ─────────────────────────────── */
.dep-summary {
    display: flex;
    gap: 0;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
}
.dep-summary-item {
    flex: 1;
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.dep-summary-item + .dep-summary-item {
    border-left: 1px solid var(--border);
}
.dep-summary-label {
    font-size: 10px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.dep-summary-value {
    font-family: "DM Mono", monospace;
    font-size: 20px;
    font-weight: 500;
    color: var(--espresso);
}

/* ── Empty state ───────────────────────────────── */
.dep-empty {
    text-align: center;
    padding: 56px 24px;
    color: var(--muted);
    font-size: 14px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
}
.dep-empty-icon {
    color: #D9CEBC;
    margin-bottom: 12px;
}
.dep-empty-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 4px;
}

/* ── Customer card ─────────────────────────────── */
.dep-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    margin-bottom: 10px;
    overflow: hidden;
    transition: box-shadow 0.14s;
}
.dep-card-head {
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    user-select: none;
}
.dep-card-head:active { background: var(--surface); }

.dep-name {
    font-size: 15px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 3px;
}
.dep-meta {
    font-size: 12px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.dep-age {
    display: inline-flex;
    align-items: center;
    height: 18px;
    padding: 0 7px;
    border-radius: 9px;
    font-size: 11px;
    font-weight: 600;
    background: var(--surface);
    color: var(--muted);
}
.dep-age.warn { background: #FDEAE3; color: var(--clay); }
.dep-age.amber { background: #FEF4E8; color: var(--terracotta); }

.dep-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.dep-balance {
    font-family: "DM Mono", monospace;
    font-size: 17px;
    font-weight: 600;
    color: var(--clay);
    white-space: nowrap;
}
.dep-chevron {
    color: var(--muted);
    transition: transform 0.18s ease;
    flex-shrink: 0;
}
.dep-card.open .dep-chevron { transform: rotate(180deg); }

/* ── Collect form ──────────────────────────────── */
.dep-form {
    display: none;
    padding: 16px 18px;
    border-top: 1px solid var(--border);
    background: var(--surface);
}
.dep-card.open .dep-form { display: block; }

.dep-form-label {
    font-size: 10px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    display: block;
    margin-bottom: 10px;
}
.dep-amount-row {
    display: flex;
    align-items: center;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
    transition: border-color 0.14s;
    margin-bottom: 10px;
}
.dep-amount-row:focus-within { border-color: var(--espresso); }
.dep-amount-prefix {
    padding: 0 12px;
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--muted);
    border-right: 1px solid var(--border);
    height: 46px;
    display: flex; align-items: center;
    background: var(--surface);
    flex-shrink: 0;
}
.dep-amount-input {
    flex: 1; height: 46px; border: none; background: transparent;
    font-family: "DM Mono", monospace; font-size: 18px;
    color: var(--espresso); padding: 0 14px; outline: none;
    -webkit-appearance: none;
}
.dep-amount-input::-webkit-outer-spin-button,
.dep-amount-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.dep-amount-input[type=number] { -moz-appearance: textfield; }

.dep-full-btn {
    width: 100%;
    text-align: left;
    font-size: 12.5px;
    color: var(--terracotta);
    cursor: pointer;
    text-decoration: none;
    background: none; border: none;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-weight: 600;
    padding: 0;
    margin-bottom: 14px;
    display: block;
    -webkit-tap-highlight-color: transparent;
}
.dep-pay-toggle {
    display: flex;
    gap: 8px;
    margin-bottom: 14px;
}
.dep-pay-opt {
    flex: 1;
    position: relative;
}
.dep-pay-opt input[type="radio"] {
    position: absolute; opacity: 0; width: 0; height: 0;
}
.dep-pay-opt label {
    display: flex; align-items: center; justify-content: center;
    height: 38px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px; font-weight: 600;
    color: var(--muted);
    background: #fff;
    cursor: pointer;
    transition: all 0.13s;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
}
.dep-pay-opt input[type="radio"]:checked + label {
    background: var(--espresso);
    border-color: var(--espresso);
    color: #fff;
}
.dep-submit-btn {
    width: 100%; height: 46px;
    background: var(--espresso); color: #fff;
    border: none; border-radius: 10px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px; font-weight: 700;
    cursor: pointer; transition: opacity 0.13s;
    -webkit-tap-highlight-color: transparent;
}
.dep-submit-btn:active { opacity: 0.8; }
</style>
@endsection

@section('content')
<div class="dep-wrap">

<h1 class="dep-heading">Deposits</h1>
<p class="dep-subheading">Collect outstanding balances from customers</p>

@if(session('success'))
<div class="flash flash-success" style="margin-bottom:16px;">{{ session('success') }}</div>
@endif

@if($customers->isEmpty())
<div class="dep-empty">
    <div class="dep-empty-icon">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
            <rect x="5" y="9" width="30" height="22" rx="4" stroke="currentColor" stroke-width="1.8"/>
            <path d="M5 17h30" stroke="currentColor" stroke-width="1.6"/>
            <path d="M11 26h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <circle cx="29" cy="26" r="1.5" fill="currentColor"/>
        </svg>
    </div>
    <div class="dep-empty-title">All clear</div>
    No outstanding deposits right now.
</div>
@else

<div class="dep-summary">
    <div class="dep-summary-item">
        <span class="dep-summary-label">Customers</span>
        <span class="dep-summary-value">{{ $customers->count() }}</span>
    </div>
    <div class="dep-summary-item">
        <span class="dep-summary-label">Total owed</span>
        <span class="dep-summary-value">{{ tenant('currency_symbol') }} {{ number_format((int)$customers->sum('balance')) }}</span>
    </div>
</div>

@foreach($customers as $c)
<div class="dep-card" id="dep-card-{{ $c->id }}">
    <div class="dep-card-head" onclick="toggleDep({{ $c->id }})">
        <div style="min-width:0;">
            <div class="dep-name">{{ $c->name }}</div>
            <div class="dep-meta">
                @if($c->phone)<span>{{ $c->phone }}</span>@endif
                <span class="dep-age {{ $c->age_days >= 30 ? 'warn' : ($c->age_days >= 14 ? 'amber' : '') }}">
                    {{ $c->age_days }}d
                </span>
            </div>
        </div>
        <div class="dep-right">
            <span class="dep-balance">{{ tenant('currency_symbol') }} {{ number_format((int)$c->balance) }}</span>
            <svg class="dep-chevron" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M2.5 4.5L7 9.5L11.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <div class="dep-form">
        <form method="POST" action="{{ route('sales.deposits.pay', $c->id) }}">
            @csrf
            <label class="dep-form-label">Amount received</label>
            <div class="dep-amount-row">
                <span class="dep-amount-prefix">{{ tenant('currency_symbol') }}</span>
                <input type="number" name="amount" class="dep-amount-input"
                       id="dep-input-{{ $c->id }}"
                       inputmode="decimal" step="1" min="1"
                       max="{{ (int)$c->balance }}"
                       placeholder="0" autocomplete="off">
            </div>
            <button type="button" class="dep-full-btn"
                    onclick="document.getElementById('dep-input-{{ $c->id }}').value='{{ (int)$c->balance }}'">
                Full balance — {{ tenant('currency_symbol') }} {{ number_format((int)$c->balance) }}
            </button>
            <div class="dep-pay-toggle">
                <div class="dep-pay-opt">
                    <input type="radio" name="payment_type" id="dep-cash-{{ $c->id }}" value="cash" checked>
                    <label for="dep-cash-{{ $c->id }}">Cash</label>
                </div>
                <div class="dep-pay-opt">
                    <input type="radio" name="payment_type" id="dep-mpesa-{{ $c->id }}" value="mpesa">
                    <label for="dep-mpesa-{{ $c->id }}">M-Pesa</label>
                </div>
            </div>
            <button type="submit" class="dep-submit-btn">Record payment →</button>
        </form>
    </div>
</div>
@endforeach

@endif

</div>
@endsection

@section('scripts')
<script>
function toggleDep(id) {
    var card = document.getElementById('dep-card-' + id);
    if (!card) return;
    var isOpen = card.classList.contains('open');
    // Close all others
    document.querySelectorAll('.dep-card.open').forEach(function(c) { c.classList.remove('open'); });
    if (!isOpen) {
        card.classList.add('open');
        var inp = document.getElementById('dep-input-' + id);
        if (inp) setTimeout(function(){ inp.focus(); }, 120);
    }
}
</script>
@endsection
