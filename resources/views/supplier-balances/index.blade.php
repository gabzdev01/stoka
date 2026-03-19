@extends('layouts.app')

@section('title', 'Supplier Balances')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Supplier balances</h1>
        <p class="page-subtitle">
            @if($suppliers->isEmpty())
                All suppliers settled
            @else
                <span style="font-family:'DM Mono',monospace;color:var(--clay);font-weight:600;">
                    {{ tenant('currency_symbol') }} {{ number_format((int)$totalOwed) }}
                </span>
                owed across {{ $suppliers->count() }} {{ $suppliers->count() === 1 ? 'supplier' : 'suppliers' }}
            @endif
        </p>
    </div>
    <a href="{{ route('restocks.create') }}" class="btn btn-primary">+ New Restock</a>
</div>
@endsection

@section('styles')
<style>
/* ── Supplier card ──────────────────────────────── */
.sup-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    margin-bottom: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(28,24,20,0.05);
}

/* ── Supplier header ────────────────────────────── */
.sc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 18px 20px;
    flex-wrap: wrap;
}
.sc-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.sc-name {
    font-size: 16px;
    font-weight: 600;
    color: var(--espresso);
}
.sc-phone {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--muted);
}
.sc-total {
    font-family: "DM Mono", monospace;
    font-size: 26px;
    font-weight: 500;
    color: var(--clay);
    white-space: nowrap;
}

/* ── Balance entries ────────────────────────────── */
.bal-entries {
    border-top: 1px solid var(--border);
}
.be-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid #F5F0EB;
    font-size: 13.5px;
}
.be-row:last-child { border-bottom: none; }
.be-left {
    display: flex;
    flex-direction: column;
    gap: 3px;
    flex: 1;
    min-width: 0;
}
.be-label {
    font-weight: 500;
    color: var(--espresso);
}
.be-date {
    font-size: 12px;
    color: var(--muted);
}
.be-right {
    text-align: right;
    flex-shrink: 0;
}
.be-balance {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    font-weight: 500;
    color: var(--clay);
}
.be-orig {
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
    font-size: 12px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.07em; white-space: nowrap;
}
.pay-input-wrap {
    display: flex; align-items: center;
    background: #fff; border: 1.5px solid var(--border);
    border-radius: 9px; overflow: hidden;
    transition: border-color 0.15s;
    flex: 1; min-width: 140px; max-width: 220px;
}
.pay-input-wrap:focus-within { border-color: var(--espresso); }
.pay-prefix {
    padding: 0 10px;
    font-family: "DM Mono", monospace; font-size: 13px; color: var(--muted);
    border-right: 1px solid var(--border); height: 40px;
    display: flex; align-items: center;
    background: var(--surface); flex-shrink: 0;
}
.pay-input {
    flex: 1; height: 40px; border: none; background: transparent;
    font-family: "DM Mono", monospace; font-size: 15px; color: var(--espresso);
    padding: 0 10px; outline: none; min-width: 0; -webkit-appearance: none;
}
.pay-input::-webkit-outer-spin-button,
.pay-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.pay-input[type=number] { -moz-appearance: textfield; }
.pay-btn {
    height: 40px; padding: 0 18px;
    background: var(--espresso); color: #fff;
    border: none; border-radius: 9px;
    font-family: "Plus Jakarta Sans", sans-serif; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: opacity 0.13s; white-space: nowrap;
}
.pay-btn:hover { opacity: 0.85; }
.pay-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.pay-shortcut {
    font-size: 12px; color: var(--terracotta); cursor: pointer;
    font-weight: 500; text-decoration: underline; white-space: nowrap;
    -webkit-tap-highlight-color: transparent;
}

/* ── Empty / flash ──────────────────────────────── */
.sup-empty {
    text-align: center; padding: 60px 20px;
    color: var(--muted); font-size: 14px; line-height: 1.7;
}
.sup-empty-title {
    font-family: "Cormorant Garamond", serif; font-size: 22px;
    font-weight: 600; color: var(--espresso); margin-bottom: 8px;
}
.sup-flash {
    display: flex; align-items: center; gap: 10px;
    background: #DFF0DD; color: var(--forest);
    border-radius: 12px; padding: 12px 16px;
    font-size: 14px; font-weight: 600; margin-bottom: 20px;
}
</style>
@endsection

@section('content')

@if(session('success'))
<div class="sup-flash">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="flex-shrink:0">
        <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
        <path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if($suppliers->isEmpty())

<div class="sup-card">
    <div class="sup-empty">
        <p class="sup-empty-title">All settled</p>
        <p>No outstanding supplier balances. Record a restock to track what you owe.</p>
        <a href="{{ route('restocks.create') }}" class="btn btn-primary" style="display:inline-block;margin-top:16px;">+ New Restock</a>
    </div>
</div>

@else

@foreach($suppliers as $supplier)
@php
    $entries   = $supplier->balances;
    $totalBal  = $entries->sum('balance');
    $oldest    = $entries->sortBy('created_at')->first();
    $ageDays   = $oldest ? (int) $oldest->created_at->diffInDays(now()) : 0;
@endphp

<div class="sup-card" id="supplier-card-{{ $supplier->id }}">

    {{-- Header --}}
    <div class="sc-header">
        <div class="sc-info">
            <span class="sc-name">{{ $supplier->name }}</span>
            @if($supplier->phone)
            <span class="sc-phone">{{ $supplier->phone }}</span>
            @endif
            @if($ageDays >= 30)
            <span style="font-size:12px;color:var(--clay);font-weight:600;margin-top:3px;">
                ⚠ Oldest balance is {{ $ageDays }} days old
            </span>
            @endif
        </div>
        <span class="sc-total">{{ tenant('currency_symbol') }} {{ number_format((int)$totalBal) }}</span>
    </div>

    {{-- Balance entries (per restock) --}}
    <div class="bal-entries">
        @foreach($entries->sortBy('created_at') as $entry)
        @php
            $paid = (float)$entry->amount_paid;
            $cost = (float)$entry->total_cost;
            $bal  = (float)$entry->balance;
        @endphp
        <div class="be-row">
            <div class="be-left">
                <span class="be-label">
                    Restock · {{ $entry->created_at->format('d M Y') }}
                </span>
                <span class="be-date">
                    Invoice {{ tenant('currency_symbol') }} {{ number_format((int)$cost) }}
                    @if($paid > 0) · Paid {{ tenant('currency_symbol') }} {{ number_format((int)$paid) }} @endif
                </span>
            </div>
            <div class="be-right">
                <div class="be-balance">{{ tenant('currency_symbol') }} {{ number_format((int)$bal) }}</div>
                @if($paid > 0)
                <div class="be-orig">of {{ tenant('currency_symbol') }} {{ number_format((int)$cost) }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Record payment --}}
    <form class="payment-section"
          id="pay-form-{{ $supplier->id }}"
          onsubmit="submitPayment(event, {{ $supplier->id }}, {{ (int)$totalBal }})">
        @csrf
        <span class="pay-label">Record payment</span>
        <div class="pay-input-wrap">
            <span class="pay-prefix">{{ tenant('currency_symbol') }}</span>
            <input
                type="number" class="pay-input"
                id="pay-input-{{ $supplier->id }}"
                name="amount" min="1" step="1" placeholder="0"
                inputmode="numeric" autocomplete="off"
                oninput="onPayInput({{ $supplier->id }})"
            >
        </div>
        <span class="pay-shortcut"
              onclick="setFullAmount({{ $supplier->id }}, {{ (int)$totalBal }})">
            Full amount
        </span>
        <button type="submit" class="pay-btn"
                id="pay-btn-{{ $supplier->id }}" disabled>
            Record
        </button>
    </form>

</div>
@endforeach

@endif

@endsection

@section('scripts')
<script>
function onPayInput(supplierId) {
    var inp = document.getElementById('pay-input-' + supplierId);
    var btn = document.getElementById('pay-btn-' + supplierId);
    var val = parseFloat(inp.value);
    btn.disabled = isNaN(val) || val <= 0;
}

function setFullAmount(supplierId, amount) {
    var inp = document.getElementById('pay-input-' + supplierId);
    inp.value = amount;
    onPayInput(supplierId);
    inp.focus();
}

function submitPayment(event, supplierId, totalBalance) {
    event.preventDefault();
    var form = document.getElementById('pay-form-' + supplierId);
    var inp  = document.getElementById('pay-input-' + supplierId);
    var btn  = document.getElementById('pay-btn-' + supplierId);
    var amount = parseFloat(inp.value);

    if (isNaN(amount) || amount <= 0) return;

    btn.disabled    = true;
    btn.textContent = 'Recording…';

    var formData = new FormData(form);
    formData.set('amount', amount);

    fetch('/supplier-balances/' + supplierId + '/payment', {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: formData,
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            if (data.outstanding <= 0) {
                var card = document.getElementById('supplier-card-' + supplierId);
                card.style.transition = 'opacity 0.4s';
                card.style.opacity    = '0';
                setTimeout(function() { card.remove(); }, 400);
            } else {
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
