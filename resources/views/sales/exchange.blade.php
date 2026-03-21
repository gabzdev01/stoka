@extends('layouts.staff')

@section('title', 'Exchange')
@section('no-search', true)

@section('styles')
<style>
.xch-wrap {
    padding: 0 0 calc(var(--tab-h) + env(safe-area-inset-bottom, 0px) + 80px);
}

/* ── Returning card ─────────────────────────────── */
.xch-returning {
    background: var(--espresso);
    padding: 20px 18px 22px;
    position: relative;
    overflow: hidden;
}
.xch-returning::after {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 140px; height: 140px;
    background: radial-gradient(circle, rgba(193,127,74,0.15) 0%, transparent 70%);
    pointer-events: none;
}
.xch-ret-eyebrow {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(250,247,242,0.45);
    margin-bottom: 10px;
}
.xch-ret-product {
    font-family: "Cormorant Garamond", serif;
    font-size: 22px;
    font-weight: 600;
    color: #FAF7F2;
    line-height: 1.15;
}
.xch-ret-meta {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: rgba(250,247,242,0.5);
    margin-top: 6px;
}
.xch-ret-price {
    font-family: "DM Mono", monospace;
    font-size: 22px;
    font-weight: 600;
    color: #C17F4A;
    margin-top: 10px;
    position: relative;
    z-index: 1;
}

/* ── Selecting section ──────────────────────────── */
.xch-selecting {
    padding: 18px 16px 0;
}
.xch-section-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 14px;
}

/* ── Category chips ─────────────────────────────── */
.xch-cats {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 14px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.xch-cats::-webkit-scrollbar { display: none; }
.xch-cat-chip {
    flex-shrink: 0;
    height: 32px;
    padding: 0 14px;
    border-radius: 16px;
    border: 1.5px solid var(--border);
    background: #fff;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: all 0.13s;
}
.xch-cat-chip.active {
    background: var(--espresso);
    border-color: var(--espresso);
    color: #fff;
}

/* ── Product list ───────────────────────────────── */
.xch-product-list {
    display: flex;
    flex-direction: column;
    gap: 0;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 14px;
}
.xch-product-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 16px;
    border-bottom: 1px solid #F5F0EB;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: background 0.1s;
    position: relative;
}
.xch-product-row:last-child { border-bottom: none; }
.xch-product-row:active { background: #FAF7F2; }
.xch-product-row.selected { background: #FDF5EC; }
.xch-product-row.hidden { display: none; }

.xch-prod-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--espresso);
    line-height: 1.3;
}
.xch-prod-cat {
    font-size: 11.5px;
    color: var(--muted);
    margin-top: 2px;
}
.xch-prod-price {
    font-family: "DM Mono", monospace;
    font-size: 14px;
    font-weight: 500;
    color: var(--espresso);
    flex-shrink: 0;
}
.xch-prod-check {
    width: 20px; height: 20px;
    border-radius: 50%;
    background: var(--terracotta);
    display: none;
    align-items: center; justify-content: center;
    flex-shrink: 0;
}
.xch-product-row.selected .xch-prod-check { display: flex; }

/* ── Variant chips ──────────────────────────────── */
.xch-variants {
    display: none;
    padding: 10px 16px 14px;
    background: #FDF5EC;
    border-top: 1px solid #F0E4D0;
    gap: 8px;
    flex-wrap: wrap;
}
.xch-variants.open { display: flex; }
.xch-variant-chip {
    height: 36px;
    padding: 0 16px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    background: #fff;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: var(--espresso);
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: all 0.13s;
}
.xch-variant-chip.selected {
    background: var(--espresso);
    border-color: var(--espresso);
    color: #fff;
}
.xch-variant-chip.oos {
    opacity: 0.35;
    pointer-events: none;
    text-decoration: line-through;
}

/* ── ML input (measured) ────────────────────────── */
.xch-ml-wrap {
    display: none;
    padding: 10px 16px 14px;
    background: #FDF5EC;
    border-top: 1px solid #F0E4D0;
    gap: 10px;
    align-items: center;
}
.xch-ml-wrap.open { display: flex; }
.xch-ml-input {
    width: 100px;
    height: 42px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-family: "DM Mono", monospace;
    font-size: 16px;
    color: var(--espresso);
    text-align: center;
    background: #fff;
    outline: none;
    transition: border-color 0.15s;
}
.xch-ml-input:focus { border-color: var(--espresso); }
.xch-ml-label {
    font-size: 13px;
    color: var(--muted);
}
.xch-ml-price {
    font-family: "DM Mono", monospace;
    font-size: 13px;
    color: var(--espresso);
    margin-left: auto;
}

/* ── Customer fields (shown when diff > 0) ──────── */
.xch-customer {
    display: none;
    flex-direction: column;
    gap: 10px;
    padding: 16px;
    background: #FFF8F2;
    border: 1px solid #E8D0C0;
    border-radius: 12px;
    margin: 0 16px 14px;
}
.xch-customer.open { display: flex; }
.xch-customer-label {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--clay);
}
.xch-customer-hint {
    font-size: 12px;
    color: var(--muted);
    margin-top: -4px;
}
.xch-customer-input {
    height: 44px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    background: #fff;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 14px;
    color: var(--espresso);
    padding: 0 14px;
    outline: none;
    transition: border-color 0.15s;
}
.xch-customer-input:focus { border-color: var(--espresso); }

/* ── Sticky action bar ──────────────────────────── */
.xch-action-bar {
    position: fixed;
    left: 0; right: 0;
    bottom: calc(var(--tab-h) + env(safe-area-inset-bottom, 0px));
    background: #fff;
    border-top: 1px solid var(--border);
    padding: 12px 16px;
    z-index: 30;
}
.xch-compare {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    min-height: 22px;
}
.xch-compare-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}
.xch-compare-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
}
.xch-compare-val {
    font-family: "DM Mono", monospace;
    font-size: 16px;
    font-weight: 600;
    color: var(--espresso);
}
.xch-compare-arrow {
    font-size: 18px;
    color: var(--border);
    padding-bottom: 2px;
}
.xch-diff-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
}
.xch-diff-val {
    font-family: "DM Mono", monospace;
    font-size: 16px;
    font-weight: 700;
}
.xch-diff-val.positive { color: var(--clay); }
.xch-diff-val.zero     { color: var(--forest); }
.xch-diff-val.negative { color: var(--muted); }

.xch-submit-btn {
    width: 100%;
    height: 50px;
    background: var(--espresso);
    color: #FAF7F2;
    border: none;
    border-radius: 13px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
    transition: opacity 0.13s;
    letter-spacing: 0.01em;
}
.xch-submit-btn:disabled { opacity: 0.35; }
.xch-submit-btn:not(:disabled):active { opacity: 0.8; }

.xch-no-results {
    padding: 24px 16px;
    text-align: center;
    font-size: 13px;
    color: var(--muted);
    display: none;
}
.xch-no-results.visible { display: block; }
</style>
@endsection

@section('content')
<div class="xch-wrap">

    {{-- Returning card --}}
    <div class="xch-returning">
        <p class="xch-ret-eyebrow">Returning</p>
        <p class="xch-ret-product">
            {{ $returnedSale->product?->name ?? '—' }}{{ $returnedSale->variant ? ' · ' . $returnedSale->variant->size : '' }}
        </p>
        <p class="xch-ret-meta">
            @if($returnedSale->product?->type === 'measured')
                {{ number_format((float)$returnedSale->quantity_or_ml, 0) }}ml ·
            @else
                Qty {{ number_format((float)$returnedSale->quantity_or_ml, 0) }} ·
            @endif
            {{ $returnedSale->created_at->format('d M, g:ia') }}
        </p>
        <p class="xch-ret-price">{{ tenant('currency_symbol') }} {{ number_format((int)$returnedSale->total) }}</p>
    </div>

    {{-- Flash error --}}
    @if(session('error'))
    <div style="margin:14px 16px 0; padding:11px 15px; background:#F5DDD8; color:var(--clay); border-radius:10px; font-size:13px; font-weight:600;">
        {{ session('error') }}
    </div>
    @endif

    {{-- Selecting section --}}
    <div class="xch-selecting">
        <p class="xch-section-label">Getting</p>

        {{-- Category filter --}}
        @php $categories = $products->pluck('category')->unique()->sort()->values(); @endphp
        <div class="xch-cats" id="xch-cats">
            <button class="xch-cat-chip active" onclick="filterCat('all', this)">All</button>
            @foreach($categories as $cat)
            <button class="xch-cat-chip" onclick="filterCat('{{ $cat }}', this)">{{ $cat }}</button>
            @endforeach
        </div>

        {{-- Product list --}}
        <div class="xch-product-list" id="xch-product-list">
            @foreach($products as $product)
            <div class="xch-product-row"
                 id="xch-prod-{{ $product->id }}"
                 data-cat="{{ $product->category }}"
                 data-type="{{ $product->type }}"
                 data-price="{{ (int) $product->shelf_price }}"
                 data-price-per-ml="{{ $product->type === 'measured' ? ($product->bottles->first()?->price_per_ml ?? 0) : 0 }}"
                 onclick="selectProduct({{ $product->id }}, '{{ addslashes($product->name) }}', {{ (int) $product->shelf_price }}, '{{ $product->type }}')">
                <div>
                    <p class="xch-prod-name">{{ $product->name }}</p>
                    <p class="xch-prod-cat">{{ $product->category }}</p>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="xch-prod-price">{{ tenant('currency_symbol') }} {{ number_format((int)$product->shelf_price) }}</span>
                    <div class="xch-prod-check">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                            <path d="M2 5l2.5 2.5L8 3" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Variant chips (hidden until product selected) --}}
            @if($product->type === 'variant')
            <div class="xch-variants" id="xch-vars-{{ $product->id }}">
                @foreach($product->variants as $v)
                <button class="xch-variant-chip {{ $v->stock <= 0 ? 'oos' : '' }}"
                        onclick="event.stopPropagation(); selectVariant({{ $v->id }}, '{{ addslashes($v->size) }}', {{ (int) $product->shelf_price }})">
                    {{ $v->size }}{{ $v->stock <= 0 ? ' ·  0' : '' }}
                </button>
                @endforeach
            </div>
            @endif

            {{-- ML input (measured) --}}
            @if($product->type === 'measured')
            @php $bottle = $product->bottles->first(); @endphp
            <div class="xch-ml-wrap" id="xch-ml-{{ $product->id }}">
                <input type="number" class="xch-ml-input" id="xch-ml-input-{{ $product->id }}"
                       placeholder="ml" min="1" max="{{ $bottle ? (int)$bottle->remaining_ml : 999 }}"
                       oninput="updateMl({{ $product->id }}, {{ $bottle?->price_per_ml ?? 0 }})">
                <span class="xch-ml-label">ml</span>
                <span class="xch-ml-price" id="xch-ml-price-{{ $product->id }}">{{ tenant('currency_symbol') }} —</span>
            </div>
            @endif

            @endforeach
        </div>
        <p class="xch-no-results" id="xch-no-results">No products in this category.</p>
    </div>

    {{-- Customer fields (shown when difference > 0) --}}
    <div class="xch-customer" id="xch-customer">
        <p class="xch-customer-label">Customer owes a balance</p>
        <p class="xch-customer-hint">Add their name to record this as a deposit balance (optional).</p>
        <input type="text" class="xch-customer-input" id="xch-cust-name"
               placeholder="Customer name" maxlength="100">
        <input type="tel" class="xch-customer-input" id="xch-cust-phone"
               placeholder="Phone number (optional)" maxlength="20">
    </div>

</div>

{{-- Hidden form --}}
<form method="POST" action="{{ route('exchange.store', $returnedSale) }}" id="xch-form">
    @csrf
    <input type="hidden" name="product_id"     id="xch-f-product-id">
    <input type="hidden" name="variant_id"     id="xch-f-variant-id">
    <input type="hidden" name="quantity_or_ml" id="xch-f-qty" value="1">
    <input type="hidden" name="actual_price"   id="xch-f-price">
    <input type="hidden" name="customer_name"  id="xch-f-cust-name">
    <input type="hidden" name="customer_phone" id="xch-f-cust-phone">
</form>

{{-- Sticky action bar --}}
<div class="xch-action-bar">
    <div class="xch-compare" id="xch-compare">
        <div class="xch-compare-item">
            <span class="xch-compare-label">Returning</span>
            <span class="xch-compare-val">{{ tenant('currency_symbol') }} {{ number_format((int)$returnedSale->total) }}</span>
        </div>
        <span class="xch-compare-arrow">→</span>
        <div class="xch-compare-item">
            <span class="xch-compare-label">Getting</span>
            <span class="xch-compare-val" id="xch-new-val">—</span>
        </div>
        <div class="xch-compare-item">
            <span class="xch-diff-label">Difference</span>
            <span class="xch-diff-val zero" id="xch-diff-val">—</span>
        </div>
    </div>
    <button class="xch-submit-btn" id="xch-submit" disabled onclick="submitExchange()">
        Select an item to exchange →
    </button>
</div>
@endsection

@section('scripts')
<script>
var returnedTotal = {{ (int) $returnedSale->total }};
var currencySymbol = '{{ tenant('currency_symbol') }}';

var selectedProductId = null;
var selectedVariantId = null;
var selectedPrice     = 0;
var selectedQty       = 1;
var selectedType      = '';

function fmt(n) {
    return Math.round(n).toLocaleString('en-KE');
}

// ── Category filter ───────────────────────────────
function filterCat(cat, btn) {
    document.querySelectorAll('.xch-cat-chip').forEach(function(c) { c.classList.remove('active'); });
    btn.classList.add('active');

    var rows = document.querySelectorAll('.xch-product-row');
    var visible = 0;
    rows.forEach(function(row) {
        var show = cat === 'all' || row.dataset.cat === cat;
        row.classList.toggle('hidden', !show);
        if (show) visible++;

        // Close variants/ml of hidden rows
        if (!show && row.id) {
            var pid = row.id.replace('xch-prod-', '');
            var vars = document.getElementById('xch-vars-' + pid);
            var ml   = document.getElementById('xch-ml-' + pid);
            if (vars) vars.classList.remove('open');
            if (ml)   ml.classList.remove('open');
        }
    });
    document.getElementById('xch-no-results').classList.toggle('visible', visible === 0);
}

// ── Product select ────────────────────────────────
function selectProduct(id, name, price, type) {
    // Deselect previous
    if (selectedProductId && selectedProductId !== id) {
        var prev = document.getElementById('xch-prod-' + selectedProductId);
        if (prev) prev.classList.remove('selected');
        var prevVars = document.getElementById('xch-vars-' + selectedProductId);
        if (prevVars) prevVars.classList.remove('open');
        var prevMl = document.getElementById('xch-ml-' + selectedProductId);
        if (prevMl) prevMl.classList.remove('open');
        // Deselect variant chips
        document.querySelectorAll('#xch-vars-' + selectedProductId + ' .xch-variant-chip').forEach(function(c) {
            c.classList.remove('selected');
        });
    }

    selectedProductId = id;
    selectedType      = type;
    selectedVariantId = null;

    var row = document.getElementById('xch-prod-' + id);
    row.classList.add('selected');

    if (type === 'unit') {
        selectedQty   = 1;
        selectedPrice = price;
        document.getElementById('xch-f-product-id').value = id;
        document.getElementById('xch-f-variant-id').value = '';
        document.getElementById('xch-f-qty').value        = 1;
        document.getElementById('xch-f-price').value      = price;
        updateCompare(price);
    } else if (type === 'variant') {
        selectedPrice = 0;
        var vars = document.getElementById('xch-vars-' + id);
        if (vars) vars.classList.add('open');
        document.getElementById('xch-f-product-id').value = id;
        updateCompare(0);
    } else if (type === 'measured') {
        selectedPrice = 0;
        var ml = document.getElementById('xch-ml-' + id);
        if (ml) {
            ml.classList.add('open');
            var inp = document.getElementById('xch-ml-input-' + id);
            if (inp) { inp.value = ''; inp.focus(); }
        }
        document.getElementById('xch-f-product-id').value = id;
        updateCompare(0);
    }
}

// ── Variant select ────────────────────────────────
function selectVariant(variantId, size, price) {
    document.querySelectorAll('#xch-vars-' + selectedProductId + ' .xch-variant-chip').forEach(function(c) {
        c.classList.remove('selected');
    });
    event.target.classList.add('selected');

    selectedVariantId = variantId;
    selectedPrice     = price;
    selectedQty       = 1;

    document.getElementById('xch-f-variant-id').value = variantId;
    document.getElementById('xch-f-qty').value        = 1;
    document.getElementById('xch-f-price').value      = price;
    updateCompare(price);
}

// ── ML input ──────────────────────────────────────
function updateMl(productId, pricePerMl) {
    var inp = document.getElementById('xch-ml-input-' + productId);
    var priceEl = document.getElementById('xch-ml-price-' + productId);
    var ml    = parseFloat(inp.value) || 0;
    var total = Math.round(ml * pricePerMl);

    priceEl.textContent = currencySymbol + ' ' + fmt(total);

    selectedQty   = ml;
    selectedPrice = total / (ml || 1);

    document.getElementById('xch-f-qty').value   = ml;
    document.getElementById('xch-f-price').value = selectedPrice;
    updateCompare(total);
}

// ── Update action bar ─────────────────────────────
function updateCompare(newTotal) {
    var newValEl  = document.getElementById('xch-new-val');
    var diffEl    = document.getElementById('xch-diff-val');
    var submitBtn = document.getElementById('xch-submit');
    var custEl    = document.getElementById('xch-customer');

    if (newTotal <= 0) {
        newValEl.textContent  = '—';
        diffEl.textContent    = '—';
        diffEl.className      = 'xch-diff-val zero';
        submitBtn.disabled    = true;
        submitBtn.textContent = 'Select an item to exchange →';
        custEl.classList.remove('open');
        return;
    }

    newValEl.textContent = currencySymbol + ' ' + fmt(newTotal);

    var diff = newTotal - returnedTotal;
    if (diff === 0) {
        diffEl.textContent = 'Even';
        diffEl.className   = 'xch-diff-val zero';
        custEl.classList.remove('open');
    } else if (diff > 0) {
        diffEl.textContent = '+' + currencySymbol + ' ' + fmt(diff);
        diffEl.className   = 'xch-diff-val positive';
        custEl.classList.add('open');
    } else {
        diffEl.textContent = currencySymbol + ' ' + fmt(Math.abs(diff));
        diffEl.className   = 'xch-diff-val negative';
        custEl.classList.remove('open');
    }

    submitBtn.disabled    = false;
    submitBtn.textContent = 'Record Exchange →';
}

// ── Submit ────────────────────────────────────────
function submitExchange() {
    if (!selectedProductId || selectedPrice <= 0) return;
    if (selectedType === 'variant' && !selectedVariantId) {
        alert('Please select a size.');
        return;
    }
    if (selectedType === 'measured' && selectedQty <= 0) {
        alert('Please enter the ml amount.');
        return;
    }

    document.getElementById('xch-f-cust-name').value  = document.getElementById('xch-cust-name').value.trim();
    document.getElementById('xch-f-cust-phone').value = document.getElementById('xch-cust-phone').value.trim();

    var btn = document.getElementById('xch-submit');
    btn.disabled = true;
    btn.textContent = 'Recording…';
    document.getElementById('xch-form').submit();
}
</script>
@endsection
