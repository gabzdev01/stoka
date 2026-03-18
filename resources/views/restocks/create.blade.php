@extends('layouts.app')

@section('title', 'New Restock')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">New Restock</h1>
        <p class="page-subtitle">Record stock received and update inventory</p>
    </div>
    <a href="{{ route('restocks.index') }}" class="btn btn-secondary">← Back</a>
</div>
@endsection

@section('styles')
<style>
/* ── Form card ─────────────────────────────────── */
.restock-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(28,24,20,0.05);
    margin-bottom: 20px;
}
.restock-section {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}
.restock-section:last-child { border-bottom: none; }
.section-label {
    font-size: 11px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.08em;
    display: block; margin-bottom: 14px;
}

/* ── Supplier select ───────────────────────────── */
.sup-select {
    width: 100%; max-width: 360px;
    height: 44px;
    padding: 0 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px;
    color: var(--espresso);
    background: #fff;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238C7B6E' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    cursor: pointer;
    transition: border-color 0.15s;
}
.sup-select:focus { outline: none; border-color: var(--espresso); }

/* ── Products table ────────────────────────────── */
.products-table {
    width: 100%;
    border-collapse: collapse;
}
.products-table th {
    font-size: 11px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.07em;
    padding: 10px 12px; text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.products-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #F5F0EB;
    font-size: 13.5px;
    color: var(--espresso);
    vertical-align: middle;
}
.products-table tbody tr:last-child td { border-bottom: none; }
.products-table tr.variant-child td:first-child {
    padding-left: 28px;
    color: var(--muted);
    font-size: 13px;
}
.prod-stock {
    font-family: "DM Mono", monospace;
    font-size: 13px;
}
.prod-stock.out  { color: var(--clay); font-weight: 600; }
.prod-stock.low  { color: var(--terracotta); }
.prod-stock.ok   { color: var(--forest); }

/* ── Qty / cost inputs ─────────────────────────── */
.qty-input, .cost-input {
    width: 90px;
    height: 36px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-family: "DM Mono", monospace;
    font-size: 14px;
    color: var(--espresso);
    padding: 0 10px;
    outline: none;
    text-align: right;
    -webkit-appearance: none;
    transition: border-color 0.15s;
}
.qty-input:focus, .cost-input:focus  { border-color: var(--espresso); }
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button,
.cost-input::-webkit-outer-spin-button,
.cost-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.qty-input[type=number], .cost-input[type=number] { -moz-appearance: textfield; }
.qty-input.has-value  { border-color: var(--terracotta); background: #FDFAF7; }

/* Category header row */
.cat-header-row td {
    background: var(--surface);
    font-size: 11px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.08em;
    padding: 8px 12px !important;
    border-bottom: 1px solid var(--border) !important;
}

/* ── Bottom form ───────────────────────────────── */
.bottom-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 32px;
    padding: 20px 24px;
}
@media (max-width: 600px) {
    .bottom-form { grid-template-columns: 1fr; gap: 16px; }
}
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label {
    font-size: 12px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.07em;
}
.ksh-wrap {
    display: flex; align-items: center;
    border: 1.5px solid var(--border);
    border-radius: 10px; overflow: hidden;
    transition: border-color 0.15s;
    background: #fff;
}
.ksh-wrap:focus-within { border-color: var(--espresso); }
.ksh-prefix {
    padding: 0 12px;
    font-family: "DM Mono", monospace;
    font-size: 13px; color: var(--muted);
    border-right: 1px solid var(--border);
    height: 44px;
    display: flex; align-items: center;
    background: var(--surface);
    flex-shrink: 0;
}
.ksh-input {
    flex: 1; height: 44px; border: none; background: transparent;
    font-family: "DM Mono", monospace; font-size: 16px;
    color: var(--espresso); padding: 0 12px; outline: none;
    min-width: 0; -webkit-appearance: none;
}
.ksh-input::-webkit-outer-spin-button,
.ksh-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.ksh-input[type=number] { -moz-appearance: textfield; }
.paid-shortcut {
    font-size: 12px; color: var(--terracotta); cursor: pointer;
    font-weight: 500; text-decoration: underline;
    -webkit-tap-highlight-color: transparent;
}
.notes-input {
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 10px 14px; font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px; color: var(--espresso); resize: vertical;
    min-height: 72px; outline: none; transition: border-color 0.15s;
    grid-column: 1 / -1;
}
.notes-input:focus { border-color: var(--espresso); }

/* ── Submit row ─────────────────────────────────── */
.submit-row {
    display: flex; justify-content: flex-end; gap: 12px;
    padding: 16px 24px; border-top: 1px solid var(--border);
    align-items: center; flex-wrap: wrap;
}
.save-btn {
    height: 44px; padding: 0 28px;
    background: var(--espresso); color: #fff;
    border: none; border-radius: 10px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px; font-weight: 700;
    cursor: pointer; transition: opacity 0.13s;
}
.save-btn:hover   { opacity: 0.85; }
.save-btn:disabled { opacity: 0.4; cursor: not-allowed; }

/* ── Error ──────────────────────────────────────── */
.form-error {
    font-size: 12px; color: var(--clay); margin-top: 2px;
}
.error-banner {
    background: #F5DDD8; color: var(--clay);
    border-radius: 10px; padding: 12px 16px;
    font-size: 13.5px; font-weight: 600; margin-bottom: 16px;
}

/* empty placeholder row */
.no-products-row td {
    text-align: center; color: var(--muted); font-size: 13px;
    padding: 20px 12px !important;
}
</style>
@endsection

@section('content')

@if($errors->any())
<div class="error-banner">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('restocks.store') }}" id="restock-form">
@csrf

<div class="restock-card">

    {{-- Supplier --}}
    <div class="restock-section">
        <span class="section-label">Supplier</span>
        <select name="supplier_id" id="supplier-select" class="sup-select" onchange="onSupplierChange()">
            <option value="">No supplier / direct purchase</option>
            @foreach($suppliers as $sup)
            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                {{ $sup->name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Products --}}
    <div class="restock-section" style="padding: 0;">
        <table class="products-table">
            <thead>
                <tr>
                    <th style="padding-left:24px;">Product</th>
                    <th>In stock</th>
                    <th>Add qty</th>
                    <th>Cost / unit</th>
                </tr>
            </thead>
            <tbody id="products-tbody">
            @php $lastCat = null; @endphp
            @foreach($products as $product)
                @if($product->category !== $lastCat)
                <tr class="cat-header-row">
                    <td colspan="4">{{ $product->category ?: 'Uncategorised' }}</td>
                </tr>
                @php $lastCat = $product->category; @endphp
                @endif

                @if($product->type === 'variant')
                {{-- Variant group header (no inputs) --}}
                <tr class="variant-group-row">
                    <td colspan="4" style="font-weight:600; padding: 10px 12px 4px; font-size:13.5px;">
                        {{ $product->name }}
                        @if($product->supplier)
                        <span style="font-size:11.5px; color:var(--muted); font-weight:400; margin-left:6px;">
                            {{ $product->supplier->name }}
                        </span>
                        @endif
                    </td>
                </tr>
                @foreach($product->variants as $variant)
                @php
                    $vstock = (int)$variant->stock;
                    $vstatus = $vstock === 0 ? 'out' : ($vstock <= (int)$product->low_stock_threshold ? 'low' : 'ok');
                @endphp
                <tr class="variant-child" data-supplier="{{ $product->supplier_id ?? '' }}">
                    <td>
                        {{ $variant->size }}{{ $variant->colour ? ' · ' . $variant->colour : '' }}
                        <input type="hidden" name="items[v{{ $variant->id }}][product_id]" value="{{ $product->id }}">
                        <input type="hidden" name="items[v{{ $variant->id }}][variant_id]" value="{{ $variant->id }}">
                    </td>
                    <td>
                        <span class="prod-stock {{ $vstatus }}">
                            {{ $vstatus === 'out' ? 'Out' : $vstock }}
                        </span>
                    </td>
                    <td>
                        <input type="number" min="0" step="1" placeholder="0"
                               name="items[v{{ $variant->id }}][qty]"
                               class="qty-input"
                               oninput="onQtyChange(this)"
                               autocomplete="off">
                    </td>
                    <td>
                        <input type="number" min="0" step="0.01" placeholder="—"
                               name="items[v{{ $variant->id }}][cost]"
                               class="cost-input"
                               oninput="recalcTotal()"
                               autocomplete="off">
                    </td>
                </tr>
                @endforeach

                @elseif($product->type === 'measured')
                @php
                    $bottle = $product->bottles->first();
                    $pct    = $bottle ? ((float)$bottle->remaining_ml / max(1, (float)$bottle->total_ml)) : 0;
                    $mstatus = !$bottle || $pct == 0 ? 'out' : ($pct < 0.20 ? 'low' : 'ok');
                @endphp
                <tr data-supplier="{{ $product->supplier_id ?? '' }}">
                    <td>
                        <span style="font-weight:500;">{{ $product->name }}</span>
                        @if($product->supplier)
                        <span style="font-size:11.5px;color:var(--muted);margin-left:6px;">{{ $product->supplier->name }}</span>
                        @endif
                        <input type="hidden" name="items[m{{ $product->id }}][product_id]" value="{{ $product->id }}">
                        <input type="hidden" name="items[m{{ $product->id }}][measured]" value="1">
                    </td>
                    <td>
                        <span class="prod-stock {{ $mstatus }}">
                            @if(!$bottle) Out
                            @else {{ number_format((float)$bottle->remaining_ml, 0) }}ml
                            @endif
                        </span>
                    </td>
                    <td>
                        <input type="number" min="0" step="1" placeholder="0 ml"
                               name="items[m{{ $product->id }}][qty]"
                               class="qty-input"
                               oninput="onQtyChange(this)"
                               autocomplete="off"
                               title="Volume of new bottle in ml">
                    </td>
                    <td>
                        <input type="number" min="0" step="0.01" placeholder="—"
                               name="items[m{{ $product->id }}][cost]"
                               class="cost-input"
                               oninput="recalcTotal()"
                               autocomplete="off">
                    </td>
                </tr>

                @else
                {{-- Unit product --}}
                @php
                    $ustock = (int)$product->stock;
                    $ustatus = $ustock === 0 ? 'out' : ($ustock <= (int)$product->low_stock_threshold ? 'low' : 'ok');
                @endphp
                <tr data-supplier="{{ $product->supplier_id ?? '' }}">
                    <td>
                        <span style="font-weight:500;">{{ $product->name }}</span>
                        @if($product->supplier)
                        <span style="font-size:11.5px;color:var(--muted);margin-left:6px;">{{ $product->supplier->name }}</span>
                        @endif
                        <input type="hidden" name="items[u{{ $product->id }}][product_id]" value="{{ $product->id }}">
                    </td>
                    <td>
                        <span class="prod-stock {{ $ustatus }}">
                            {{ $ustatus === 'out' ? 'Out' : $ustock }}
                        </span>
                    </td>
                    <td>
                        <input type="number" min="0" step="1" placeholder="0"
                               name="items[u{{ $product->id }}][qty]"
                               class="qty-input"
                               oninput="onQtyChange(this)"
                               autocomplete="off">
                    </td>
                    <td>
                        <input type="number" min="0" step="0.01" placeholder="—"
                               name="items[u{{ $product->id }}][cost]"
                               class="cost-input"
                               oninput="recalcTotal()"
                               autocomplete="off">
                    </td>
                </tr>
                @endif

            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Bottom: cost + payment + notes --}}
    <div class="bottom-form">
        <div class="form-group">
            <label class="form-label" for="total-cost">Total invoice</label>
            <div class="ksh-wrap">
                <span class="ksh-prefix">KSh</span>
                <input type="number" min="0" step="1" id="total-cost"
                       name="total_cost" class="ksh-input"
                       value="{{ old('total_cost') }}"
                       placeholder="0"
                       autocomplete="off"
                       oninput="onCostChange()">
            </div>
            @error('total_cost')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="amount-paid">
                Paid today
                <span class="paid-shortcut" onclick="setFullPaid()" style="margin-left:8px;text-transform:none;font-weight:500;">Full amount</span>
            </label>
            <div class="ksh-wrap">
                <span class="ksh-prefix">KSh</span>
                <input type="number" min="0" step="1" id="amount-paid"
                       name="amount_paid" class="ksh-input"
                       value="{{ old('amount_paid', 0) }}"
                       placeholder="0"
                       autocomplete="off">
            </div>
            @error('amount_paid')
            <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="grid-column:1/-1;">
            <label class="form-label" for="notes">Notes (optional)</label>
            <textarea name="notes" id="notes" class="notes-input"
                      placeholder="e.g. Received from Ali, invoice #123">{{ old('notes') }}</textarea>
        </div>
    </div>

    {{-- Submit --}}
    <div class="submit-row">
        <a href="{{ route('restocks.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="save-btn" id="save-btn" disabled>Save Restock</button>
    </div>

</div>

</form>

@endsection

@section('scripts')
<script>
// Track whether any qty has been entered
function onQtyChange(input) {
    var val = parseFloat(input.value);
    if (val > 0) {
        input.classList.add('has-value');
    } else {
        input.classList.remove('has-value');
    }
    recalcTotal();
    updateSaveBtn();
}

function recalcTotal() {
    // Auto-sum qty * cost for each row
    var rows = document.querySelectorAll('#products-tbody tr');
    var sum = 0;
    rows.forEach(function(row) {
        var qtyInp  = row.querySelector('.qty-input');
        var costInp = row.querySelector('.cost-input');
        if (!qtyInp || !costInp) return;
        var qty  = parseFloat(qtyInp.value)  || 0;
        var cost = parseFloat(costInp.value) || 0;
        sum += qty * cost;
    });
    if (sum > 0) {
        var totalField = document.getElementById('total-cost');
        if (!totalField._manuallyEdited) {
            totalField.value = Math.round(sum);
            onCostChange();
        }
    }
    updateSaveBtn();
}

function onCostChange() {
    // If user manually edits total, mark it
    document.getElementById('total-cost')._manuallyEdited = false; // reset on each change (auto or manual)
    updateSaveBtn();
}

// If user directly edits total cost, mark as manual so auto-sum stops overriding
document.getElementById('total-cost').addEventListener('input', function() {
    this._manuallyEdited = true;
    updateSaveBtn();
});

function setFullPaid() {
    var total = parseFloat(document.getElementById('total-cost').value) || 0;
    document.getElementById('amount-paid').value = total;
}

function updateSaveBtn() {
    var hasQty = false;
    document.querySelectorAll('.qty-input').forEach(function(inp) {
        if (parseFloat(inp.value) > 0) hasQty = true;
    });
    document.getElementById('save-btn').disabled = !hasQty;
}

// Supplier filter: highlight rows belonging to selected supplier
// (All products always shown — supplier just sets who invoice is from)
function onSupplierChange() {
    var suppId = document.getElementById('supplier-select').value;
    document.querySelectorAll('#products-tbody tr[data-supplier]').forEach(function(row) {
        var rowSup = row.getAttribute('data-supplier');
        if (!suppId || rowSup === suppId) {
            row.style.background = '';
        } else {
            row.style.background = '';
        }
    });
}
</script>
@endsection
