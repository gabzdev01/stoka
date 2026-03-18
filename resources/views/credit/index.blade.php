@extends('layouts.app')

@section('title', 'Credit')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Credit owed</h1>
        <p class="page-subtitle">
            @if($customers->isEmpty())
                No outstanding credit
            @else
                <span style="font-family:'DM Mono',monospace;color:var(--clay);font-weight:600;">
                    Ksh {{ number_format((int)$totalOwed) }}
                </span>
                owed across {{ $customers->count() }} {{ $customers->count() === 1 ? 'customer' : 'customers' }}
            @endif
        </p>
    </div>
</div>
@endsection

@section('styles')
<style>
/* ── Customer card ──────────────────────────────── */
.credit-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    margin-bottom: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(28,24,20,0.05);
}

/* ── Customer header ────────────────────────────── */
.cc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px 20px;
    flex-wrap: wrap;
}
.cc-customer {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.cc-name {
    font-size: 16px;
    font-weight: 600;
    color: var(--espresso);
}
.cc-phone {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--muted);
}
.cc-total-owed {
    font-family: "DM Mono", monospace;
    font-size: 26px;
    font-weight: 500;
    color: var(--clay);
    white-space: nowrap;
}

/* ── Credit entries table ───────────────────────── */
.credit-entries {
    border-top: 1px solid var(--border);
}
.ce-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid #F5F0EB;
    font-size: 13.5px;
}
.ce-row:last-child { border-bottom: none; }
.ce-left {
    display: flex;
    flex-direction: column;
    gap: 3px;
    flex: 1;
    min-width: 0;
}
.ce-product {
    font-weight: 500;
    color: var(--espresso);
}
.ce-date {
    font-size: 12px;
    color: var(--muted);
}
.ce-age-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 2px 7px;
    border-radius: 10px;
    margin-left: 6px;
}
.age-ok     { background: #EEE4D5; color: var(--muted); }
.age-30     { background: #F5EADB; color: var(--terracotta); }
.age-60     { background: #F5DDD8; color: var(--clay); }
.ce-right {
    text-align: right;
    flex-shrink: 0;
}
.ce-balance {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    font-weight: 500;
    color: var(--clay);
}
.ce-orig {
    font-size: 11.5px;
    color: var(--muted);
    margin-top: 2px;
}

/* ── Record payment section ─────────────────────── */
.payment-section {
    border-top: 1px solid var(--border);
    padding: 16px 20px;
    background: #FDFAF7;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.pay-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
    white-space: nowrap;
}
.pay-input-wrap {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    overflow: hidden;
    transition: border-color 0.15s;
    flex: 1;
    min-width: 140px;
    max-width: 220px;
}
.pay-input-wrap:focus-within { border-color: var(--espresso); }
.pay-prefix {
    padding: 0 10px;
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--muted);
    border-right: 1px solid var(--border);
    height: 40px;
    display: flex;
    align-items: center;
    background: var(--surface);
    flex-shrink: 0;
}
.pay-input {
    flex: 1;
    height: 40px;
    border: none;
    background: transparent;
    font-family: "DM Mono", monospace;
    font-size: 15px;
    color: var(--espresso);
    padding: 0 10px;
    outline: none;
    min-width: 0;
    -webkit-appearance: none;
}
.pay-input::-webkit-outer-spin-button,
.pay-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.pay-input[type=number] { -moz-appearance: textfield; }
.pay-btn {
    height: 40px;
    padding: 0 18px;
    background: var(--espresso);
    color: #fff;
    border: none;
    border-radius: 9px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.13s;
    white-space: nowrap;
}
.pay-btn:hover { opacity: 0.85; }
.pay-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.pay-shortcut {
    font-size: 12px;
    color: var(--terracotta);
    cursor: pointer;
    font-weight: 500;
    text-decoration: underline;
    white-space: nowrap;
    -webkit-tap-highlight-color: transparent;
}

/* ── Empty state ────────────────────────────────── */
.credit-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.7;
}
.credit-empty-title {
    font-family: "Cormorant Garamond", serif;
    font-size: 22px;
    font-weight: 600;
    color: var(--espresso);
    margin-bottom: 8px;
}

/* ── Flash ──────────────────────────────────────── */
.credit-flash {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #DFF0DD;
    color: var(--forest);
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
}
</style>
@endsection

@section('content')

@if(session('success'))
<div class="credit-flash">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="flex-shrink:0">
        <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
        <path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if($customers->isEmpty())

<div class="credit-empty">
    <p class="credit-empty-title">No outstanding credit</p>
    <p>All customer balances are settled.</p>
</div>

@else

@foreach($customers as $customer)
@php
    $entries      = $customer->openCredit;
    $totalBalance = $entries->sum('balance');
    $oldestEntry  = $entries->sortBy('created_at')->first();
    $ageDays      = $oldestEntry ? (int) $oldestEntry->created_at->diffInDays(now()) : 0;
@endphp

<div class="credit-card" id="customer-card-{{ $customer->id }}">

    {{-- Customer header --}}
    <div class="cc-header">
        <div class="cc-customer">
            <span class="cc-name">{{ $customer->name }}</span>
            @if($customer->phone)
            <span class="cc-phone">{{ $customer->phone }}</span>
            @endif
            @if($ageDays >= 60)
            <span style="font-size:12px;color:var(--clay);font-weight:600;margin-top:3px;">⚠ Oldest tab is {{ $ageDays }} days old</span>
            @elseif($ageDays >= 30)
            <span style="font-size:12px;color:var(--terracotta);margin-top:3px;">{{ $ageDays }} days since first credit</span>
            @endif
        </div>
        <span class="cc-total-owed">Ksh {{ number_format((int)$totalBalance) }}</span>
    </div>

    {{-- Credit entries --}}
    <div class="credit-entries">
        @foreach($entries->sortBy('created_at') as $entry)
        @php
            $days    = (int) $entry->created_at->diffInDays(now());
            $ageClass = $days >= 60 ? 'age-60' : ($days >= 30 ? 'age-30' : 'age-ok');
            $ageText  = $days === 0 ? 'Today' : $days . 'd ago';
            $product  = $entry->sale?->product?->name ?? 'Credit sale';
        @endphp
        <div class="ce-row">
            <div class="ce-left">
                <span class="ce-product">
                    {{ $product }}
                    <span class="ce-age-badge {{ $ageClass }}">{{ $ageText }}</span>
                </span>
                <span class="ce-date">{{ $entry->created_at->format('d M Y') }}</span>
            </div>
            <div class="ce-right">
                <div class="ce-balance">Ksh {{ number_format((int)$entry->balance) }}</div>
                @if((float)$entry->paid > 0)
                <div class="ce-orig">of Ksh {{ number_format((int)$entry->amount) }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Record payment --}}
    <form class="payment-section"
          id="pay-form-{{ $customer->id }}"
          onsubmit="submitPayment(event, {{ $customer->id }}, {{ (int)$totalBalance }})">
        @csrf
        <span class="pay-label">Record payment</span>
        <div class="pay-input-wrap">
            <span class="pay-prefix">KSh</span>
            <input
                type="number"
                class="pay-input"
                id="pay-input-{{ $customer->id }}"
                name="amount"
                min="1"
                step="1"
                placeholder="0"
                inputmode="numeric"
                autocomplete="off"
                oninput="onPayInput({{ $customer->id }}, {{ (int)$totalBalance }})"
            >
        </div>
        <span class="pay-shortcut"
              onclick="setFullAmount({{ $customer->id }}, {{ (int)$totalBalance }})">
            Full amount
        </span>
        <button type="submit"
                class="pay-btn"
                id="pay-btn-{{ $customer->id }}"
                disabled>
            Record
        </button>
    </form>

</div>
@endforeach

@endif

@endsection

@section('scripts')
<script>
function onPayInput(customerId, maxAmount) {
    var inp = document.getElementById('pay-input-' + customerId);
    var btn = document.getElementById('pay-btn-' + customerId);
    var val = parseFloat(inp.value);
    btn.disabled = isNaN(val) || val <= 0;
}

function setFullAmount(customerId, amount) {
    var inp = document.getElementById('pay-input-' + customerId);
    inp.value = amount;
    onPayInput(customerId, amount);
    inp.focus();
}

function submitPayment(event, customerId, totalBalance) {
    event.preventDefault();
    var form = document.getElementById('pay-form-' + customerId);
    var inp  = document.getElementById('pay-input-' + customerId);
    var btn  = document.getElementById('pay-btn-' + customerId);
    var amount = parseFloat(inp.value);

    if (isNaN(amount) || amount <= 0) return;

    btn.disabled    = true;
    btn.textContent = 'Recording…';

    var formData = new FormData(form);
    formData.set('amount', amount);

    fetch('/credit/' + customerId + '/payment', {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: formData,
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            if (data.outstanding <= 0) {
                // Customer fully settled — remove card with fade
                var card = document.getElementById('customer-card-' + customerId);
                card.style.transition = 'opacity 0.4s';
                card.style.opacity    = '0';
                setTimeout(function() { card.remove(); }, 400);
            } else {
                // Reload to show updated balances
                window.location.reload();
            }
        } else {
            btn.disabled    = false;
            btn.textContent = 'Record';
            alert(data.error || 'Could not record payment.');
        }
    })
    .catch(function() {
        btn.disabled    = false;
        btn.textContent = 'Record';
        alert('Network error. Please try again.');
    });
}
</script>
@endsection
