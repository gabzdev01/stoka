@extends('layouts.app')

@section('title', isset($product) ? 'Edit: ' . $product->name : 'Add Product')

@section('header')
    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('products.index') }}"
           style="color:var(--muted); text-decoration:none; font-size:13px; display:flex; align-items:center; gap:5px;">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Products
        </a>
        <span style="color:var(--border);">/</span>
        <h1 class="page-title">{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h1>
    </div>
@endsection

@section('styles')
<style>
    /* ── Type selector ───────────────────────────────────── */
    .type-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .type-card {
        position: relative;
        cursor: pointer;
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 16px 14px;
        background: var(--surface);
        transition: border-color 0.13s, background 0.13s;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .type-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .type-card.selected {
        border-color: var(--terracotta);
        background: var(--parchment);
    }
    .type-card-name {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--espresso);
        display: block;
    }
    .type-card-desc {
        font-size: 12px;
        color: var(--muted);
        line-height: 1.4;
        display: block;
    }

    /* ── Form layout ─────────────────────────────────────── */
    .form-card {
        background: var(--parchment);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .section-title {
        font-family: "Cormorant Garamond", serif;
        font-size: 18px;
        font-weight: 600;
        color: var(--espresso);
        margin-bottom: 18px;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-group { margin-bottom: 18px; }
    .form-group:last-child { margin-bottom: 0; }

    .form-label {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 7px;
    }
    .form-label .required { color: var(--clay); margin-left: 2px; }

    .form-input,
    .form-select {
        width: 100%;
        padding: 10px 13px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13.5px;
        color: var(--espresso);
        outline: none;
        transition: border-color 0.13s, background 0.13s;
        box-sizing: border-box;
    }
    .form-input:focus,
    .form-select:focus {
        border-color: var(--terracotta);
        background: var(--parchment);
    }
    .form-input.is-error,
    .form-select.is-error { border-color: var(--clay); }

    /* Price field */
    .price-wrap { position: relative; }
    .price-prefix {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12.5px;
        color: var(--muted);
        font-family: "DM Mono", monospace;
        pointer-events: none;
        line-height: 1;
    }
    .price-wrap .form-input {
        padding-left: 46px;
        font-family: "DM Mono", monospace;
    }

    .field-error {
        display: block;
        font-size: 12px;
        color: var(--clay);
        margin-top: 5px;
    }

    /* ── Toggle switch ───────────────────────────────────── */
    .toggle-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
    }
    .toggle-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
    .toggle-track {
        position: absolute;
        inset: 0;
        background: var(--border);
        border-radius: 11px;
        cursor: pointer;
        transition: background 0.15s;
    }
    .toggle-track::before {
        content: '';
        position: absolute;
        width: 16px; height: 16px;
        background: white;
        border-radius: 50%;
        top: 3px; left: 3px;
        transition: transform 0.15s;
    }
    .toggle-switch input:checked + .toggle-track { background: var(--forest); }
    .toggle-switch input:checked + .toggle-track::before { transform: translateX(18px); }
    .toggle-label { font-size: 13.5px; color: var(--espresso); cursor: pointer; line-height: 1.3; }
    .toggle-sublabel { font-size: 12px; color: var(--muted); margin-top: 2px; }

    /* ── Variant rows ────────────────────────────────────── */
    .variant-headers {
        display: grid;
        grid-template-columns: 1fr 1fr 90px 36px;
        gap: 10px;
        margin-bottom: 6px;
    }
    .variant-col-head {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }
    .variant-row {
        display: grid;
        grid-template-columns: 1fr 1fr 90px 36px;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }
    .btn-remove {
        width: 36px;
        height: 38px;
        background: none;
        border: 1px solid var(--border);
        border-radius: 7px;
        color: var(--muted);
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.13s, border-color 0.13s, color 0.13s;
        padding: 0;
        flex-shrink: 0;
    }
    .btn-remove:hover { background: #F9E8E4; border-color: var(--clay); color: var(--clay); }
    .btn-add-variant {
        width: 100%;
        background: none;
        border: 1px dashed var(--border);
        border-radius: 8px;
        padding: 10px;
        font-size: 13px;
        color: var(--muted);
        cursor: pointer;
        font-family: "Plus Jakarta Sans", sans-serif;
        transition: border-color 0.13s, color 0.13s;
        text-align: center;
        margin-top: 4px;
    }
    .btn-add-variant:hover { border-color: var(--terracotta); color: var(--terracotta); }

    /* ── Conditional sections ────────────────────────────── */
    .conditional-section { display: none; }
    .conditional-section.visible { display: block; }

    /* ── Error banner ────────────────────────────────────── */
    .error-banner {
        background: #F9E8E4;
        border: 1px solid #ECC9C2;
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 20px;
    }

    /* ── Form actions ────────────────────────────────────── */
    .form-actions { display: flex; gap: 10px; padding-top: 4px; }

    @media (max-width: 580px) {
        .type-cards { grid-template-columns: 1fr; }
        .form-grid-2 { grid-template-columns: 1fr; }
        .variant-headers { grid-template-columns: 1fr 1fr 70px 34px; }
        .variant-row     { grid-template-columns: 1fr 1fr 70px 34px; }
    }
</style>
@endsection

@section('content')
@php
    $isEdit      = isset($product);
    $currentType = old('type', $isEdit ? $product->type : 'unit');

    if (old('variants')) {
        $initVariants = old('variants');
    } elseif ($isEdit && $product->variants->count()) {
        $initVariants = $product->variants->map(fn($v) => [
            'size'   => $v->size,
            'colour' => $v->colour,
            'stock'  => $v->stock,
        ])->toArray();
    } else {
        $initVariants = [['size' => '', 'colour' => '', 'stock' => '']];
    }

    $activeBottle   = $isEdit ? $product->bottles->firstWhere('active', true) : null;
    $initTotalMl    = old('total_ml',    $activeBottle?->total_ml    ?? '');
    $initPricePerMl = old('price_per_ml', $activeBottle?->price_per_ml ?? '');
@endphp

@if($errors->any())
<div class="error-banner">
    <p style="font-size:13px; font-weight:600; color:var(--clay); margin-bottom:6px;">Please fix the following:</p>
    <ul style="font-size:12.5px; color:var(--clay); padding-left:16px; margin:0;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST"
      action="{{ $isEdit ? route('products.update', $product) : route('products.store') }}"
      id="product-form">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- ── Type ─────────────────────────────────────────── --}}
    <div class="form-card">
        <p class="section-title" style="margin-bottom:14px;">Product Type</p>
        <div class="type-cards" id="type-cards">
            @php
                $types = [
                    'unit'     => ['Unit Item',    'Bags, shoes, jewellery — sold as one piece'],
                    'measured' => ['Measured',     'Perfumes, oils — sold by ml or weight'],
                    'variant'  => ['Has Variants', 'Clothing, footwear — comes in sizes or colours'],
                ];
            @endphp
            @foreach($types as $val => [$label, $desc])
            <label class="type-card {{ $currentType === $val ? 'selected' : '' }}" data-type="{{ $val }}">
                <input type="radio" name="type" value="{{ $val }}" {{ $currentType === $val ? 'checked' : '' }}>
                <span class="type-card-name">{{ $label }}</span>
                <span class="type-card-desc">{{ $desc }}</span>
            </label>
            @endforeach
        </div>
        @error('type') <span class="field-error" style="margin-top:10px; display:block;">{{ $message }}</span> @enderror
    </div>

    {{-- ── Product Details ──────────────────────────────── --}}
    <div class="form-card">
        <p class="section-title">Product Details</p>

        <div class="form-group">
            <label class="form-label" for="f-name">Name <span class="required">*</span></label>
            <input class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                   type="text" id="f-name" name="name"
                   value="{{ old('name', $isEdit ? $product->name : '') }}"
                   placeholder="e.g. Floral Silk Dress" autocomplete="off">
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="f-category">Category</label>
                <input class="form-input {{ $errors->has('category') ? 'is-error' : '' }}"
                       type="text" id="f-category" name="category"
                       value="{{ old('category', $isEdit ? $product->category : '') }}"
                       placeholder="e.g. Dresses, Perfumes" autocomplete="off">
                @error('category') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="f-supplier">Supplier</label>
                <select class="form-select" id="f-supplier" name="supplier_id">
                    <option value="">— No supplier —</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}"
                            {{ (int) old('supplier_id', $isEdit ? $product->supplier_id : '') === $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ── Pricing ───────────────────────────────────────── --}}
    <div class="form-card">
        <p class="section-title">Pricing</p>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="f-shelf-price">Selling Price <span class="required">*</span></label>
                <div class="price-wrap">
                    <span class="price-prefix">KSh</span>
                    <input class="form-input {{ $errors->has('shelf_price') ? 'is-error' : '' }}"
                           type="number" id="f-shelf-price" name="shelf_price"
                           value="{{ old('shelf_price', $isEdit ? $product->shelf_price : '') }}"
                           placeholder="0.00" step="0.01" min="0">
                </div>
                @error('shelf_price') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="f-floor-price">Floor Price
                    <span style="font-weight:400; text-transform:none; letter-spacing:0; font-size:11px;">(minimum)</span>
                </label>
                <div class="price-wrap">
                    <span class="price-prefix">KSh</span>
                    <input class="form-input {{ $errors->has('floor_price') ? 'is-error' : '' }}"
                           type="number" id="f-floor-price" name="floor_price"
                           value="{{ old('floor_price', $isEdit ? $product->floor_price : '') }}"
                           placeholder="0.00" step="0.01" min="0">
                </div>
                @error('floor_price') <span class="field-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="toggle-row" style="border-top:1px solid var(--border); padding-top:14px; margin-top:6px;">
            <label class="toggle-switch">
                <input type="checkbox" name="is_bargainable" id="f-bargainable" value="1"
                       {{ old('is_bargainable', $isEdit ? $product->is_bargainable : false) ? 'checked' : '' }}>
                <span class="toggle-track"></span>
            </label>
            <div>
                <label class="toggle-label" for="f-bargainable">Allow bargaining</label>
                <p class="toggle-sublabel">Staff can negotiate price down to the floor price</p>
            </div>
        </div>
    </div>

    {{-- ── Stock (unit type only) ───────────────────────── --}}
    <div class="form-card conditional-section {{ $currentType === 'unit' ? 'visible' : '' }}"
         id="section-stock">
        <p class="section-title">Stock</p>

        <div class="toggle-row" style="margin-bottom:16px;">
            <label class="toggle-switch">
                <input type="checkbox" name="track_stock" id="f-track-stock" value="1"
                       {{ old('track_stock', $isEdit ? $product->track_stock : true) ? 'checked' : '' }}
                       onchange="toggleStockFields(this.checked)">
                <span class="toggle-track"></span>
            </label>
            <div>
                <label class="toggle-label" for="f-track-stock">Track stock levels</label>
                <p class="toggle-sublabel">Count units and get alerted when stock is low</p>
            </div>
        </div>

        <div id="stock-fields">
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="f-stock">Current Stock</label>
                    <input class="form-input" style="font-family:'DM Mono',monospace;"
                           type="number" id="f-stock" name="stock"
                           value="{{ old('stock', $isEdit ? $product->stock : 0) }}"
                           placeholder="0" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label" for="f-threshold">Low Stock Alert At</label>
                    <input class="form-input" style="font-family:'DM Mono',monospace;"
                           type="number" id="f-threshold" name="low_stock_threshold"
                           value="{{ old('low_stock_threshold', $isEdit ? $product->low_stock_threshold : 5) }}"
                           placeholder="5" min="0">
                </div>
            </div>
        </div>
    </div>

    {{-- ── Variants (variant type only) ────────────────── --}}
    <div class="form-card conditional-section {{ $currentType === 'variant' ? 'visible' : '' }}"
         id="section-variants">
        <p class="section-title">Variants</p>
        <p style="font-size:12.5px; color:var(--muted); margin-bottom:18px;">
            Each size or colour option with its own stock count.
        </p>

        <div class="variant-headers">
            <span class="variant-col-head">Size</span>
            <span class="variant-col-head">Colour</span>
            <span class="variant-col-head">Stock</span>
            <span></span>
        </div>

        <div id="variant-rows"></div>

        <button type="button" class="btn-add-variant" onclick="addVariantRow()">
            + Add another variant
        </button>
    </div>

    {{-- ── Bottle / Measured ────────────────────────────── --}}
    <div class="form-card conditional-section {{ $currentType === 'measured' ? 'visible' : '' }}"
         id="section-bottle">
        <p class="section-title">Bottle / Container</p>
        <p style="font-size:12.5px; color:var(--muted); margin-bottom:18px;">
            Enter the bottle size and cost per ml to track volume. Leave blank if not tracking by volume.
        </p>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="f-total-ml">Bottle Size (ml)</label>
                <input class="form-input" style="font-family:'DM Mono',monospace;"
                       type="number" id="f-total-ml" name="total_ml"
                       value="{{ $initTotalMl }}"
                       placeholder="e.g. 100" step="0.1" min="0">
            </div>
            <div class="form-group">
                <label class="form-label" for="f-price-per-ml">Price per ml (KSh)</label>
                <input class="form-input" style="font-family:'DM Mono',monospace;"
                       type="number" id="f-price-per-ml" name="price_per_ml"
                       value="{{ $initPricePerMl }}"
                       placeholder="e.g. 2.50" step="0.01" min="0">
            </div>
        </div>
    </div>

    {{-- ── Actions ──────────────────────────────────────── --}}
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ $isEdit ? 'Save Changes' : 'Add Product' }}
        </button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
    </div>

</form>
@endsection

@section('scripts')
<script>
var INIT_TYPE     = '{{ $currentType }}';
var INIT_VARIANTS = @json($initVariants);
var variantIdx    = 0;

// ── Type card selection ─────────────────────────────────
document.querySelectorAll('.type-card').forEach(function(card) {
    card.addEventListener('click', function() {
        document.querySelectorAll('.type-card').forEach(function(c) {
            c.classList.remove('selected');
        });
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
        updateSections(card.dataset.type);
    });
});

function updateSections(type) {
    document.getElementById('section-stock').classList.toggle('visible', type === 'unit');
    document.getElementById('section-variants').classList.toggle('visible', type === 'variant');
    document.getElementById('section-bottle').classList.toggle('visible', type === 'measured');
}

// ── Stock fields opacity ────────────────────────────────
function toggleStockFields(checked) {
    var el = document.getElementById('stock-fields');
    el.style.opacity       = checked ? '1' : '0.4';
    el.style.pointerEvents = checked ? '' : 'none';
}

// ── Variant rows ────────────────────────────────────────
function addVariantRow(size, colour, stock) {
    var idx = variantIdx++;
    var row = document.createElement('div');
    row.className = 'variant-row';
    row.id        = 'vrow-' + idx;

    var sizeVal   = esc(size   !== undefined ? size   : '');
    var colourVal = esc(colour !== undefined ? colour : '');
    var stockVal  = stock !== undefined && stock !== '' ? esc(stock) : '';

    row.innerHTML =
        '<input class="form-input" type="text"   name="variants[' + idx + '][size]"   value="' + sizeVal   + '" placeholder="e.g. M, L, XL">' +
        '<input class="form-input" type="text"   name="variants[' + idx + '][colour]" value="' + colourVal + '" placeholder="e.g. Black">' +
        '<input class="form-input" type="number" name="variants[' + idx + '][stock]"  value="' + stockVal  + '" placeholder="0" min="0" style="font-family:\'DM Mono\',monospace;">' +
        '<button type="button" class="btn-remove" onclick="removeVariantRow(\'vrow-' + idx + '\')" title="Remove">×</button>';

    document.getElementById('variant-rows').appendChild(row);
}

function removeVariantRow(id) {
    if (document.querySelectorAll('.variant-row').length <= 1) return;
    var el = document.getElementById(id);
    if (el) el.remove();
}

function esc(s) {
    return String(s)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

// ── Init ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    // Render initial variant rows
    if (INIT_VARIANTS && INIT_VARIANTS.length) {
        INIT_VARIANTS.forEach(function(v) {
            addVariantRow(
                v.size   !== null ? v.size   : '',
                v.colour !== null ? v.colour : '',
                v.stock  !== undefined ? v.stock : ''
            );
        });
    } else {
        addVariantRow('', '', '');
    }

    // Restore stock field opacity
    var trackCb = document.getElementById('f-track-stock');
    if (trackCb) toggleStockFields(trackCb.checked);
});
</script>
@endsection
