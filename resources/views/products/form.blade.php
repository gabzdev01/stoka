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
        transition: border-color 0.15s, background 0.15s;
        display: flex;
        flex-direction: column;
        gap: 5px;
        user-select: none;
    }
    .type-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
    .type-card.selected { border-color: var(--terracotta); background: var(--parchment); }
    .type-card-name { font-size: 13.5px; font-weight: 600; color: var(--espresso); display: block; }
    .type-card-desc { font-size: 12px; color: var(--muted); line-height: 1.4; display: block; }

    /* ── Measured hint box ───────────────────────────────── */
    .measured-hint {
        display: none;
        align-items: flex-start;
        gap: 12px;
        background: #FDF5EC;
        border: 1px solid #EDD9BC;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #7A5020;
        line-height: 1.5;
    }
    .measured-hint.visible { display: flex; }
    .measured-hint svg { flex-shrink: 0; margin-top: 1px; }

    /* ── Form cards ──────────────────────────────────────── */
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
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
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
    .form-label-note {
        font-weight: 400;
        text-transform: none;
        letter-spacing: 0;
        font-size: 11px;
        color: var(--muted);
    }

    .form-input, .form-select {
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
    .form-input:focus, .form-select:focus { border-color: var(--terracotta); background: var(--parchment); }
    .form-input.is-error, .form-select.is-error { border-color: var(--clay); }

    /* Price field */
    .price-wrap { position: relative; }
    .price-prefix {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        font-size: 12.5px; color: var(--muted); font-family: "DM Mono", monospace;
        pointer-events: none; line-height: 1;
    }
    .price-wrap .form-input { padding-left: 46px; font-family: "DM Mono", monospace; }

    .field-error { display: block; font-size: 12px; color: var(--clay); margin-top: 5px; }

    /* ── Toggle ──────────────────────────────────────────── */
    .toggle-row { display: flex; align-items: center; gap: 12px; padding: 12px 0; }
    .toggle-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
    .toggle-track {
        position: absolute; inset: 0; background: var(--border);
        border-radius: 11px; cursor: pointer; transition: background 0.15s;
    }
    .toggle-track::before {
        content: ''; position: absolute; width: 16px; height: 16px;
        background: white; border-radius: 50%; top: 3px; left: 3px; transition: transform 0.15s;
    }
    .toggle-switch input:checked + .toggle-track { background: var(--forest); }
    .toggle-switch input:checked + .toggle-track::before { transform: translateX(18px); }
    .toggle-label { font-size: 13.5px; color: var(--espresso); cursor: pointer; line-height: 1.3; }
    .toggle-sublabel { font-size: 12px; color: var(--muted); margin-top: 2px; }

    /* ── Floor price slide-in ────────────────────────────── */
    .floor-price-wrap {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.28s ease, opacity 0.22s ease, margin-top 0.28s ease;
        margin-top: 0;
    }
    .floor-price-wrap.visible {
        max-height: 100px;
        opacity: 1;
        margin-top: 16px;
    }

    /* ── Chips ───────────────────────────────────────────── */
    .chip-group { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 4px; }
    .chip {
        padding: 7px 15px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        color: var(--muted);
        background: var(--surface);
        cursor: pointer;
        font-family: "Plus Jakarta Sans", sans-serif;
        transition: border-color 0.13s, color 0.13s, background 0.13s;
        line-height: 1.4;
    }
    .chip:hover { border-color: var(--terracotta); color: var(--terracotta); background: var(--parchment); }
    .chip.active { background: var(--terracotta); border-color: var(--terracotta); color: #fff; }
    .chip-add {
        padding: 7px 13px;
        border: 1.5px dashed var(--border);
        border-radius: 20px;
        font-size: 13px;
        color: var(--muted);
        background: none;
        cursor: pointer;
        font-family: "Plus Jakarta Sans", sans-serif;
        transition: border-color 0.13s, color 0.13s;
        line-height: 1.4;
    }
    .chip-add:hover { border-color: var(--terracotta); color: var(--terracotta); }

    /* ML chips are single-select */
    .ml-chips .chip.active { background: #4A6741; border-color: #4A6741; }

    /* ── Chip stock rows ─────────────────────────────────── */
    .chip-stocks {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 14px;
    }
    .chip-stock-row {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 12px;
    }
    .chip-stock-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--espresso);
        min-width: 28px;
    }
    .chip-stock-input {
        width: 62px;
        padding: 5px 8px;
        background: var(--parchment);
        border: 1px solid var(--border);
        border-radius: 6px;
        font-family: "DM Mono", monospace;
        font-size: 13px;
        color: var(--espresso);
        outline: none;
        text-align: center;
        transition: border-color 0.13s;
    }
    .chip-stock-input:focus { border-color: var(--terracotta); }
    .chip-stock-unit { font-size: 12px; color: var(--muted); }

    /* ── Custom chip input ───────────────────────────────── */
    .custom-chip-wrap {
        display: none;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }
    .custom-chip-wrap.visible { display: flex; }
    .custom-chip-input {
        padding: 8px 11px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13px;
        color: var(--espresso);
        outline: none;
        width: 160px;
        transition: border-color 0.13s;
    }
    .custom-chip-input:focus { border-color: var(--terracotta); }
    .btn-add-chip {
        padding: 8px 14px;
        background: var(--espresso);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 500;
        cursor: pointer;
        font-family: "Plus Jakarta Sans", sans-serif;
        transition: opacity 0.13s;
    }
    .btn-add-chip:hover { opacity: 0.8; }

    /* ── Section divider label ───────────────────────────── */
    .subsection-label {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 10px;
        margin-top: 20px;
        display: block;
    }
    .subsection-label:first-child { margin-top: 0; }

    /* ── Conditional sections ────────────────────────────── */
    .conditional-section { display: none; }
    .conditional-section.visible { display: block; }

    /* ── Error banner ────────────────────────────────────── */
    .error-banner {
        background: #F9E8E4; border: 1px solid #ECC9C2;
        border-radius: 8px; padding: 12px 14px; margin-bottom: 20px;
    }

    /* ── Form actions ────────────────────────────────────── */
    .form-actions { display: flex; gap: 10px; padding-top: 4px; }

    /* ── Custom select dropdown ─────────────────────────────────── */
    .custom-select { position: relative; user-select: none; }
    .cs-trigger {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 13px; background: var(--surface);
        border: 1px solid var(--border); border-radius: 8px;
        cursor: pointer; font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13.5px; color: var(--espresso);
        transition: border-color 0.13s, background 0.13s; min-height: 42px;
    }
    .cs-trigger:hover, .cs-trigger.open { border-color: var(--terracotta); background: var(--parchment); }
    .cs-trigger.is-error { border-color: var(--clay); }
    .cs-arrow { width: 14px; height: 14px; color: var(--muted); flex-shrink: 0; margin-left: 8px; transition: transform 0.2s; }
    .cs-trigger.open .cs-arrow { transform: rotate(180deg); }
    .cs-value { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cs-dropdown {
        position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 200;
        background: #fff; border: 1px solid var(--border); border-radius: 10px;
        box-shadow: 0 4px 20px rgba(28,24,20,0.12); display: none; overflow: hidden;
    }
    .cs-dropdown.open { display: block; }
    .cs-search { border-bottom: 1px solid var(--border); }
    .cs-search input {
        width: 100%; padding: 10px 14px; border: none;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 13.5px;
        color: var(--espresso); outline: none; background: #FDFAF7; box-sizing: border-box;
    }
    .cs-options { max-height: 240px; overflow-y: auto; }
    .cs-option {
        padding: 10px 14px; font-size: 13.5px; color: var(--espresso);
        cursor: pointer; display: flex; align-items: center; justify-content: space-between;
        transition: background 0.1s;
    }
    .cs-option:hover { background: var(--surface); }
    .cs-option.selected { color: var(--terracotta); font-weight: 600; }
    .cs-option.selected::after { content: "✓"; color: var(--terracotta); font-size: 12px; }
    .cs-option.hidden { display: none; }

    @media (max-width: 580px) {
        .type-cards { grid-template-columns: 1fr; }
        .form-grid-2 { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
@php
    $isEdit      = isset($product);
    $currentType = old('type', $isEdit ? $product->type : 'unit');
    $currentCat  = old('category', $isEdit ? ($product->category ?? '') : '');

    $allCats = [
        'Dresses', 'Tops & Blouses', 'Trousers & Shorts', 'Shirts',
        'Jackets & Coats', 'Shoes & Sandals', 'Bags & Handbags',
        'Perfumes (Bottled)', 'Perfumes (Bulk)', 'Body Sprays & Mists',
        'Jewellery', 'Hair Products', 'Caps & Hats', 'Accessories',
        "Kids' Clothing",
    ];
    $typeCats = $allCats;

    $presetSizes   = ['XS','S','M','L','XL','XXL'];
    $presetColours = ['Black','White','Brown','Beige','Navy','Red','Green'];
    $presetMls     = [30, 50, 100, 200, 500];

    if (old('variants')) {
        $rawVariants = old('variants');
    } elseif ($isEdit && $product->variants->count()) {
        $rawVariants = $product->variants->map(fn($v) => ['size' => $v->size, 'colour' => $v->colour, 'stock' => $v->stock])->toArray();
    } else {
        $rawVariants = [];
    }
    $initSizes = $initColours = $customSizes = $customColours = [];
    foreach ($rawVariants as $v) {
        $sz = $v['size'] ?? ''; $cl = $v['colour'] ?? ''; $st = (int)($v['stock'] ?? 0);
        if ($sz) { if (in_array($sz, $presetSizes)) $initSizes[$sz] = $st; else $customSizes[$sz] = $st; }
        if ($cl) { if (in_array($cl, $presetColours)) $initColours[$cl] = $st; else $customColours[$cl] = $st; }
    }

    $activeBottle   = $isEdit ? $product->bottles->firstWhere('active', true) : null;
    $initTotalMl    = (int) old('total_ml', $activeBottle?->total_ml ?? 0);
    $initPricePerMl = old('price_per_ml', $activeBottle?->price_per_ml ?? '');
    $selectedMlPreset = in_array($initTotalMl, $presetMls) ? $initTotalMl : null;
    $isCustomMl = $initTotalMl > 0 && !$selectedMlPreset;

    $isBargainable = (bool) old('is_bargainable', $isEdit ? ($product->is_bargainable ?? false) : false);
    $trackStock    = (bool) old('track_stock',    $isEdit ? ($product->track_stock    ?? true)  : true);

    // Is the current category "Other" (not in type list)?
    $catIsOther = $currentCat && !in_array($currentCat, $allCats);
@endphp

@if($errors->any())
<div class="error-banner">
    <p style="font-size:13px; font-weight:600; color:var(--clay); margin-bottom:6px;">Please fix the following:</p>
    <ul style="font-size:12.5px; color:var(--clay); padding-left:16px; margin:0;">
        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
    </ul>
</div>
@endif

<form method="POST"
      action="{{ $isEdit ? route('products.update', $product) : route('products.store') }}"
      id="product-form">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Hidden category value — JS keeps it synced --}}
    <input type="hidden" name="category" id="f-category" value="{{ $currentCat }}">

    {{-- ── What are you selling? ────────────────────────── --}}
    <div class="form-card">
        <p class="section-title" style="margin-bottom:14px;">What are you selling?</p>
        <div class="type-cards">
            <label class="type-card {{ $currentType === 'unit' ? 'selected' : '' }}" data-type="unit">
                <input type="radio" name="type" value="unit" {{ $currentType === 'unit' ? 'checked' : '' }}>
                <span class="type-card-name">By the piece</span>
                <span class="type-card-desc">A bag, a cap, a sealed perfume bottle, an empty bottle</span>
            </label>
            <label class="type-card {{ $currentType === 'measured' ? 'selected' : '' }}" data-type="measured">
                <input type="radio" name="type" value="measured" {{ $currentType === 'measured' ? 'checked' : '' }}>
                <span class="type-card-name">By ml — bulk / decant</span>
                <span class="type-card-desc">You pour from your supply into the customer's bottle</span>
            </label>
            <label class="type-card {{ $currentType === 'variant' ? 'selected' : '' }}" data-type="variant">
                <input type="radio" name="type" value="variant" {{ $currentType === 'variant' ? 'checked' : '' }}>
                <span class="type-card-name">Comes in sizes or colours</span>
                <span class="type-card-desc">A dress in S, M, L — or shoes in different colours</span>
            </label>
        </div>
        @error('type') <span class="field-error" style="margin-top:10px; display:block;">{{ $message }}</span> @enderror
    </div>

    {{-- ── Measured context note ────────────────────────── --}}
    <div class="measured-hint {{ $currentType === 'measured' ? 'visible' : '' }}" id="measured-hint">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="color:#C17F4A; margin-top:1px;">
            <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
            <path d="M8 7v4M8 5h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <span>This is for perfume you sell by pouring into the customer's bottle. Add it once — each time you restock a new bottle, you record it under Restocks. For sealed bottles sold whole, use <strong>By the piece</strong> instead.</span>
    </div>

    {{-- ── Product Details ──────────────────────────────── --}}
    <div class="form-card">
        <p class="section-title">Product Details</p>

        <div class="form-group">
            <label class="form-label" for="f-name">Name <span class="required">*</span></label>
            <input class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                   type="text" id="f-name" name="name"
                   value="{{ old('name', $isEdit ? $product->name : '') }}"
                   placeholder="{{ $currentType === 'measured' ? 'e.g. Versace Eros Bulk, Chanel No.5 Oil' : ($currentType === 'variant' ? 'e.g. Floral Midi Dress, Men\'s Oxford Shirt' : 'e.g. Leather Handbag, Cap, Empty 50ml Bottle') }}"
                   autocomplete="off">
            @error('name') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="f-category-select">Category</label>

                <div class="custom-select" id="category-select">
                    <div class="cs-trigger {{ $errors->has('category') ? 'is-error' : '' }}" onclick="csToggle('category-select')">
                        <span class="cs-value" id="category-display">@if($catIsOther){{ $currentCat }}@elseif($currentCat){{ $currentCat }}@else— Choose a category —@endif</span>
                        <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="cs-dropdown">
                        <div class="cs-search"><input type="text" placeholder="Search categories…" oninput="csSearch('category-select', this.value)"></div>
                        <div class="cs-options" id="category-options">
                            <div class="cs-option {{ !$currentCat ? 'selected' : '' }}" data-value="" onclick="csSelectCategory('', '— Choose a category —')">— Choose a category —</div>
                            @foreach($typeCats as $cat)
                            <div class="cs-option {{ (!$catIsOther && $currentCat === $cat) ? 'selected' : '' }}" data-value="{{ $cat }}" onclick="csSelectCategory('{{ $cat }}', '{{ $cat }}')">{{ $cat }}</div>
                            @endforeach
                            <div class="cs-option {{ $catIsOther ? 'selected' : '' }}" data-value="__other__" onclick="csSelectCategory('__other__', 'Other…')">Other…</div>
                        </div>
                    </div>
                </div>

                <div class="custom-chip-wrap {{ $catIsOther ? 'visible' : '' }}" id="category-other-wrap" style="margin-top:8px;">
                    <input type="text"
                           class="form-input custom-chip-input"
                           id="f-category-other"
                           value="{{ $catIsOther ? $currentCat : '' }}"
                           placeholder="e.g. Swimwear, Socks"
                           style="width:100%;"
                           oninput="document.getElementById('f-category').value = this.value">
                </div>

                @error('category') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Supplier</label>
                @php
                    $selSuppId   = (int) old('supplier_id', $isEdit ? ($product->supplier_id ?? 0) : 0);
                    $selSuppName = '— No supplier —';
                    foreach ($suppliers as $_s) {
                        if ((int)$_s->id === $selSuppId && $selSuppId > 0) { $selSuppName = $_s->name; break; }
                    }
                @endphp
                <input type="hidden" name="supplier_id" id="f-supplier" value="{{ $selSuppId ?: '' }}">
                <div class="custom-select" id="supplier-select">
                    <div class="cs-trigger" onclick="csToggle('supplier-select')">
                        <span class="cs-value" id="supplier-display">{{ $selSuppName }}</span>
                        <svg class="cs-arrow" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="cs-dropdown">
                        <div class="cs-options">
                            <div class="cs-option {{ !$selSuppId ? 'selected' : '' }}" data-value="" onclick="csSelectSupplier('', '— No supplier —')">— No supplier —</div>
                            @foreach($suppliers as $s)
                            <div class="cs-option {{ $selSuppId === (int)$s->id ? 'selected' : '' }}" data-value="{{ $s->id }}" onclick="csSelectSupplier('{{ $s->id }}', '{{ addslashes($s->name) }}')">{{ $s->name }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Gender (optional) ──────────────────────── --}}
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label" style="margin-bottom:10px;">
                Who is this for?
                <span style="font-weight:400; color:var(--muted); margin-left:6px; text-transform:none; letter-spacing:0;">(optional)</span>
            </label>

            <input type="hidden" name="gender" id="f-gender"
                   value="{{ old('gender', $isEdit ? ($product->gender ?? '') : '') }}">

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                @foreach(['male' => "Men's", 'female' => "Women's", 'unisex' => 'Unisex'] as $val => $label)
                <button type="button"
                        class="gender-pill"
                        data-value="{{ $val }}"
                        onclick="toggleGender(this)"
                        style="
                            padding: 8px 18px;
                            border-radius: 100px;
                            border: 1.5px solid var(--border);
                            background: transparent;
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-size: 13px;
                            font-weight: 600;
                            color: var(--muted);
                            cursor: pointer;
                            transition: background 0.13s, border-color 0.13s, color 0.13s;
                            min-height: 36px;
                            -webkit-tap-highlight-color: transparent;
                        ">{{ $label }}</button>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── Pricing ───────────────────────────────────────── --}}
    <div class="form-card">
        <p class="section-title">Pricing</p>

        <div class="form-group">
            <label class="form-label" id="shelf-price-label" for="f-shelf-price">
                <span id="shelf-price-label-text">{{ $currentType === 'measured' ? 'Price per ml' : 'Selling Price' }}</span>
                <span class="required">*</span>
            </label>
            <div class="price-wrap" style="max-width:220px;">
                <span class="price-prefix">KSh</span>
                <input class="form-input {{ $errors->has('shelf_price') ? 'is-error' : '' }}"
                       type="number" id="f-shelf-price" name="shelf_price"
                       value="{{ old('shelf_price', $isEdit ? $product->shelf_price : '') }}"
                       placeholder="{{ $currentType === 'measured' ? '0.00  (e.g. 16.50 per ml)' : '0.00' }}"
                       step="0.01" min="0"
                       oninput="document.getElementById('f-price-per-ml-mirror').value = this.value">
            </div>
            @error('shelf_price') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        {{-- Mirror shelf_price into price_per_ml for measured type --}}
        <input type="hidden" id="f-price-per-ml-mirror" name="price_per_ml"
               value="{{ old('price_per_ml', $initPricePerMl ?: ($isEdit ? ($product->shelf_price ?? '') : '')) }}">

        <div class="toggle-row" style="border-top:1px solid var(--border); padding-top:14px; margin-top:4px;">
            <label class="toggle-switch">
                <input type="checkbox" name="is_bargainable" id="f-bargainable" value="1"
                       {{ $isBargainable ? 'checked' : '' }}
                       onchange="toggleBargaining(this.checked)">
                <span class="toggle-track"></span>
            </label>
            <div>
                <label class="toggle-label" for="f-bargainable">Allow bargaining</label>
                <p class="toggle-sublabel">Staff can negotiate down to a minimum price you set</p>
            </div>
        </div>

        {{-- Floor price — hidden until bargaining is on --}}
        <div class="floor-price-wrap {{ $isBargainable ? 'visible' : '' }}" id="floor-price-wrap">
            <label class="form-label" for="f-floor-price">
                Minimum (floor) price <span class="form-label-note">— staff cannot go below this</span>
            </label>
            <div class="price-wrap" style="max-width:220px;">
                <span class="price-prefix">KSh</span>
                <input class="form-input {{ $errors->has('floor_price') ? 'is-error' : '' }}"
                       type="number" id="f-floor-price" name="floor_price"
                       value="{{ old('floor_price', $isEdit ? $product->floor_price : '') }}"
                       placeholder="0.00" step="0.01" min="0">
            </div>
            @error('floor_price') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- ── Stock (unit type) ────────────────────────────── --}}
    <div class="form-card conditional-section {{ $currentType === 'unit' ? 'visible' : '' }}" id="section-stock">
        <p class="section-title">Stock</p>

        <div class="toggle-row" style="margin-bottom:16px;">
            <label class="toggle-switch">
                <input type="checkbox" name="track_stock" id="f-track-stock" value="1"
                       {{ $trackStock ? 'checked' : '' }}
                       onchange="toggleStockFields(this.checked)">
                <span class="toggle-track"></span>
            </label>
            <div>
                <label class="toggle-label" for="f-track-stock">Track stock levels</label>
                <p class="toggle-sublabel">Count units and get alerted when stock is low</p>
            </div>
        </div>

        <div id="stock-fields" style="{{ !$trackStock ? 'opacity:0.4; pointer-events:none;' : '' }}">
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="f-stock">How many do you have right now?</label>
                    <input class="form-input" style="font-family:'DM Mono',monospace;"
                           type="number" id="f-stock" name="stock"
                           value="{{ old('stock', $isEdit ? $product->stock : 0) }}"
                           placeholder="0" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label" for="f-threshold">Alert me when stock falls below</label>
                    <input class="form-input" style="font-family:'DM Mono',monospace;"
                           type="number" id="f-threshold" name="low_stock_threshold"
                           value="{{ old('low_stock_threshold', $isEdit ? $product->low_stock_threshold : 5) }}"
                           placeholder="5" min="0">
                </div>
            </div>
        </div>
    </div>

    {{-- ── Bottle size (measured type) ──────────────────── --}}
    <div class="form-card conditional-section {{ $currentType === 'measured' ? 'visible' : '' }}" id="section-bottle">
        <p class="section-title">Bottle size</p>
        <p style="font-size:12.5px; color:var(--muted); margin-bottom:18px; line-height:1.5;">
            How large is the bottle or container you're selling from? This lets Stoka track how much is left.
        </p>

        <label class="form-label" style="margin-bottom:10px;">Select bottle size</label>
        <div class="chip-group ml-chips" id="ml-chips">
            @foreach($presetMls as $ml)
            <button type="button" class="chip {{ $selectedMlPreset === $ml ? 'active' : '' }}"
                    data-value="{{ $ml }}"
                    onclick="selectMlChip(this)">{{ $ml }}ml</button>
            @endforeach
            <button type="button" class="chip {{ $isCustomMl ? 'active' : '' }}"
                    data-value="custom"
                    onclick="selectMlChip(this)">Custom</button>
        </div>

        <div id="custom-ml-wrap" style="margin-top:12px; {{ $isCustomMl ? '' : 'display:none;' }}">
            <div style="display:flex; align-items:center; gap:10px; max-width:220px;">
                <input class="form-input" style="font-family:'DM Mono',monospace;"
                       type="number" id="f-total-ml-custom" placeholder="e.g. 750"
                       value="{{ $isCustomMl ? $initTotalMl : '' }}"
                       step="1" min="1"
                       oninput="document.getElementById('f-total-ml').value = this.value">
                <span style="font-size:13px; color:var(--muted); white-space:nowrap;">ml</span>
            </div>
        </div>

        <input type="hidden" id="f-total-ml" name="total_ml"
               value="{{ $initTotalMl ?: '' }}">

        <p style="font-size:12px; color:var(--muted); margin-top:14px;">
            The price per ml was set in the Pricing section above.
        </p>
    </div>

    {{-- ── Variants (sizes & colours) ──────────────────── --}}
    <div class="form-card conditional-section {{ $currentType === 'variant' ? 'visible' : '' }}" id="section-variants">
        <p class="section-title">Sizes &amp; Colours</p>
        <p style="font-size:12.5px; color:var(--muted); margin-bottom:20px; line-height:1.5;">
            Select the sizes and colours you stock. Each one you pick up gets its own stock count.
        </p>

        {{-- Sizes --}}
        <span class="subsection-label">Sizes</span>
        <div class="chip-group" id="size-chips">
            @foreach($presetSizes as $sz)
            <button type="button"
                    class="chip size-chip {{ isset($initSizes[$sz]) ? 'active' : '' }}"
                    data-value="{{ $sz }}"
                    onclick="toggleSizeChip(this)">{{ $sz }}</button>
            @endforeach
            @foreach($customSizes as $sz => $st)
            <button type="button"
                    class="chip size-chip active"
                    data-value="{{ $sz }}"
                    onclick="toggleSizeChip(this)">{{ $sz }}</button>
            @endforeach
            <button type="button" class="chip-add" id="size-custom-btn" onclick="showCustomChipInput('size')">+ Custom</button>
        </div>

        <div class="custom-chip-wrap" id="custom-size-wrap">
            <input type="text" class="custom-chip-input" id="custom-size-input" placeholder="e.g. 36, 38, Free size"
                   onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomChip('size');}">
            <button type="button" class="btn-add-chip" onclick="addCustomChip('size')">Add</button>
            <button type="button" class="chip-add" onclick="hideCustomChipInput('size')">Cancel</button>
        </div>

        <div class="chip-stocks" id="size-stocks">
            @foreach($initSizes as $sz => $st)
            <div class="chip-stock-row" id="srow-{{ Str::slug($sz) }}">
                <span class="chip-stock-label">{{ $sz }}</span>
                <input type="number" class="chip-stock-input size-stock-val" data-size="{{ $sz }}"
                       id="sstock-{{ Str::slug($sz) }}" value="{{ $st }}" min="0" placeholder="0">
                <span class="chip-stock-unit">pcs</span>
            </div>
            @endforeach
            @foreach($customSizes as $sz => $st)
            <div class="chip-stock-row" id="srow-{{ Str::slug($sz) }}">
                <span class="chip-stock-label">{{ $sz }}</span>
                <input type="number" class="chip-stock-input size-stock-val" data-size="{{ $sz }}"
                       id="sstock-{{ Str::slug($sz) }}" value="{{ $st }}" min="0" placeholder="0">
                <span class="chip-stock-unit">pcs</span>
            </div>
            @endforeach
        </div>

        {{-- Colours --}}
        <span class="subsection-label" style="margin-top:24px;">Colours <span class="form-label-note" style="text-transform:none; letter-spacing:0;">(optional)</span></span>
        <div class="chip-group" id="colour-chips">
            @foreach($presetColours as $cl)
            <button type="button"
                    class="chip colour-chip {{ isset($initColours[$cl]) ? 'active' : '' }}"
                    data-value="{{ $cl }}"
                    onclick="toggleColourChip(this)">{{ $cl }}</button>
            @endforeach
            @foreach($customColours as $cl => $st)
            <button type="button"
                    class="chip colour-chip active"
                    data-value="{{ $cl }}"
                    onclick="toggleColourChip(this)">{{ $cl }}</button>
            @endforeach
            <button type="button" class="chip-add" id="colour-custom-btn" onclick="showCustomChipInput('colour')">+ Custom</button>
        </div>

        <div class="custom-chip-wrap" id="custom-colour-wrap">
            <input type="text" class="custom-chip-input" id="custom-colour-input" placeholder="e.g. Olive, Dusty Pink"
                   onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomChip('colour');}">
            <button type="button" class="btn-add-chip" onclick="addCustomChip('colour')">Add</button>
            <button type="button" class="chip-add" onclick="hideCustomChipInput('colour')">Cancel</button>
        </div>

        <div class="chip-stocks" id="colour-stocks">
            @foreach($initColours as $cl => $st)
            <div class="chip-stock-row" id="crow-{{ Str::slug($cl) }}">
                <span class="chip-stock-label">{{ $cl }}</span>
                <input type="number" class="chip-stock-input colour-stock-val" data-colour="{{ $cl }}"
                       id="cstock-{{ Str::slug($cl) }}" value="{{ $st }}" min="0" placeholder="0">
                <span class="chip-stock-unit">pcs</span>
            </div>
            @endforeach
            @foreach($customColours as $cl => $st)
            <div class="chip-stock-row" id="crow-{{ Str::slug($cl) }}">
                <span class="chip-stock-label">{{ $cl }}</span>
                <input type="number" class="chip-stock-input colour-stock-val" data-colour="{{ $cl }}"
                       id="cstock-{{ Str::slug($cl) }}" value="{{ $st }}" min="0" placeholder="0">
                <span class="chip-stock-unit">pcs</span>
            </div>
            @endforeach
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
// ── Data ────────────────────────────────────────────────
var CATEGORIES = [
    'Dresses', 'Tops & Blouses', 'Trousers & Shorts', 'Shirts',
    'Jackets & Coats', 'Shoes & Sandals', 'Bags & Handbags',
    'Perfumes (Bottled)', 'Perfumes (Bulk)', 'Body Sprays & Mists',
    'Jewellery', 'Hair Products', 'Caps & Hats', 'Accessories',
    "Kids' Clothing"
];
var NAME_PLACEHOLDERS = {
    unit:     'e.g. Leather Handbag, Cap, Empty 50ml Bottle',
    measured: 'e.g. Versace Eros Bulk, Chanel No.5 Oil',
    variant:  'e.g. Floral Midi Dress, Men\'s Oxford Shirt'
};
var PRICE_LABELS = {
    unit:     'Selling Price',
    measured: 'Price per ml',
    variant:  'Selling Price'
};
var SHELF_PLACEHOLDERS = {
    unit:     '0.00',
    measured: '0.00',
    variant:  '0.00'
};

// ── Type switching ──────────────────────────────────────
document.querySelectorAll('.type-card').forEach(function(card) {
    card.addEventListener('click', function() {
        document.querySelectorAll('.type-card').forEach(function(c) { c.classList.remove('selected'); });
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
        switchType(card.dataset.type);
    });
});

function switchType(type) {
    // Show/hide sections
    document.getElementById('section-stock').classList.toggle('visible', type === 'unit');
    document.getElementById('section-bottle').classList.toggle('visible', type === 'measured');
    document.getElementById('section-variants').classList.toggle('visible', type === 'variant');
    document.getElementById('measured-hint').classList.toggle('visible', type === 'measured');

    // Name placeholder
    document.getElementById('f-name').placeholder = NAME_PLACEHOLDERS[type] || '';

    // Price label
    document.getElementById('shelf-price-label-text').textContent = PRICE_LABELS[type] || 'Selling Price';

    // Category options
    updateCategoryOptions(type);

    // Auto-set category for measured if blank
    if (type === 'measured') {
        var current = document.getElementById('f-category').value;
        if (!current) setCategoryValue('Perfumes (Bulk)');
    }
}

// ── Category ────────────────────────────────────────────
function updateCategoryOptions(type) {
    var current = document.getElementById('f-category').value;
    var cats    = CATEGORIES;
    var container = document.getElementById('category-options');
    container.innerHTML = '';

    function makeOpt(val, label) {
        var d = document.createElement('div');
        d.className = 'cs-option' + (val === current ? ' selected' : '');
        d.setAttribute('data-value', val);
        d.textContent = label;
        var v = val, l = label;
        d.onclick = function() { csSelectCategory(v, l); };
        container.appendChild(d);
    }

    makeOpt('', '— Choose a category —');
    cats.forEach(function(cat) { makeOpt(cat, cat); });
    makeOpt('__other__', 'Other…');

    var inList = cats.indexOf(current) !== -1;
    if (current && !inList) {
        document.getElementById('category-display').textContent = current;
        document.getElementById('category-other-wrap').classList.add('visible');
        document.getElementById('f-category-other').value = current;
    } else {
        document.getElementById('category-display').textContent = current || '— Choose a category —';
        document.getElementById('category-other-wrap').classList.remove('visible');
    }
}

function onCategorySelect(val) {
    var otherWrap = document.getElementById('category-other-wrap');
    var hiddenCat = document.getElementById('f-category');
    if (val === '__other__') {
        otherWrap.classList.add('visible');
        hiddenCat.value = document.getElementById('f-category-other').value;
        document.getElementById('f-category-other').focus();
    } else {
        otherWrap.classList.remove('visible');
        document.getElementById('f-category-other').value = '';
        hiddenCat.value = val;
    }
}

function setCategoryValue(val) {
    document.getElementById('f-category').value = val;
    document.getElementById('category-display').textContent = val || '— Choose a category —';
    document.querySelectorAll('#category-select .cs-option').forEach(function(o) {
        o.classList.toggle('selected', o.getAttribute('data-value') === val);
    });
    document.getElementById('category-other-wrap').classList.remove('visible');
}

// ── Bargaining / floor price ────────────────────────────
function toggleBargaining(checked) {
    var wrap = document.getElementById('floor-price-wrap');
    if (checked) {
        wrap.classList.add('visible');
    } else {
        wrap.classList.remove('visible');
        document.getElementById('f-floor-price').value = '';
    }
}

// ── Stock field opacity ─────────────────────────────────
function toggleStockFields(checked) {
    var el = document.getElementById('stock-fields');
    el.style.opacity       = checked ? '1' : '0.4';
    el.style.pointerEvents = checked ? '' : 'none';
}

// ── ML chips (single-select) ────────────────────────────
function selectMlChip(chip) {
    document.querySelectorAll('#ml-chips .chip').forEach(function(c) { c.classList.remove('active'); });
    chip.classList.add('active');
    var customWrap = document.getElementById('custom-ml-wrap');
    var hiddenMl   = document.getElementById('f-total-ml');
    if (chip.dataset.value === 'custom') {
        customWrap.style.display = 'block';
        hiddenMl.value = document.getElementById('f-total-ml-custom').value;
        document.getElementById('f-total-ml-custom').focus();
    } else {
        customWrap.style.display = 'none';
        document.getElementById('f-total-ml-custom').value = '';
        hiddenMl.value = chip.dataset.value;
    }
}

// Keep custom ml input synced
document.addEventListener('DOMContentLoaded', function() {
    var customMlInput = document.getElementById('f-total-ml-custom');
    if (customMlInput) {
        customMlInput.addEventListener('input', function() {
            document.getElementById('f-total-ml').value = this.value;
        });
    }
});

// ── Size chips ──────────────────────────────────────────
function toggleSizeChip(chip) {
    var val  = chip.dataset.value;
    var slug = slugify(val);
    if (chip.classList.contains('active')) {
        chip.classList.remove('active');
        var row = document.getElementById('srow-' + slug);
        if (row) row.remove();
    } else {
        chip.classList.add('active');
        addStockRow('size', val, slug, 0);
    }
}

// ── Colour chips ────────────────────────────────────────
function toggleColourChip(chip) {
    var val  = chip.dataset.value;
    var slug = slugify(val);
    if (chip.classList.contains('active')) {
        chip.classList.remove('active');
        var row = document.getElementById('crow-' + slug);
        if (row) row.remove();
    } else {
        chip.classList.add('active');
        addStockRow('colour', val, slug, 0);
    }
}

function addStockRow(dimension, label, slug, initStock) {
    var container = document.getElementById(dimension === 'size' ? 'size-stocks' : 'colour-stocks');
    var row = document.createElement('div');
    row.className = 'chip-stock-row';
    row.id = (dimension === 'size' ? 'srow-' : 'crow-') + slug;
    var inputId = (dimension === 'size' ? 'sstock-' : 'cstock-') + slug;
    row.innerHTML =
        '<span class="chip-stock-label">' + escHtml(label) + '</span>' +
        '<input type="number" class="chip-stock-input" id="' + inputId + '"' +
        '       value="' + (initStock || 0) + '" min="0" placeholder="0">' +
        '<span class="chip-stock-unit">pcs</span>';
    container.appendChild(row);
    row.querySelector('input').focus();
}

// ── Custom chips ────────────────────────────────────────
function showCustomChipInput(dimension) {
    document.getElementById('custom-' + dimension + '-wrap').classList.add('visible');
    document.getElementById('custom-' + dimension + '-input').focus();
}
function hideCustomChipInput(dimension) {
    document.getElementById('custom-' + dimension + '-wrap').classList.remove('visible');
    document.getElementById('custom-' + dimension + '-input').value = '';
}
function addCustomChip(dimension) {
    var input = document.getElementById('custom-' + dimension + '-input');
    var val   = input.value.trim();
    if (!val) return;
    var slug  = slugify(val);

    // Don't add duplicates
    if (document.querySelector('#' + dimension + '-chips .chip[data-value="' + val.replace(/"/g, '\\"') + '"]')) {
        input.value = '';
        hideCustomChipInput(dimension);
        return;
    }

    var container = document.getElementById(dimension + '-chips');
    var addBtn    = document.getElementById(dimension + '-custom-btn');
    var chip = document.createElement('button');
    chip.type      = 'button';
    chip.className = 'chip ' + dimension + '-chip active';
    chip.dataset.value = val;
    chip.textContent   = val;
    chip.onclick = dimension === 'size'
        ? function() { toggleSizeChip(this); }
        : function() { toggleColourChip(this); };
    container.insertBefore(chip, addBtn);

    addStockRow(dimension, val, slug, 0);
    input.value = '';
    hideCustomChipInput(dimension);
}

// ── Form submission — generate variant hidden inputs ────
document.getElementById('product-form').addEventListener('submit', function() {
    var typeInput = document.querySelector('input[name="type"]:checked');
    if (!typeInput || typeInput.value !== 'variant') return;

    var form = this;
    form.querySelectorAll('.variant-gen').forEach(function(el) { el.remove(); });

    var idx = 0;
    function appendVariant(size, colour, stock) {
        var base = 'variants[' + idx + ']';
        [['size', size], ['colour', colour], ['stock', stock]].forEach(function(pair) {
            var inp = document.createElement('input');
            inp.type      = 'hidden';
            inp.name      = base + '[' + pair[0] + ']';
            inp.value     = pair[1] !== null && pair[1] !== undefined ? pair[1] : '';
            inp.className = 'variant-gen';
            form.appendChild(inp);
        });
        idx++;
    }

    document.querySelectorAll('#size-chips .chip.active').forEach(function(chip) {
        var val   = chip.dataset.value;
        var slug  = slugify(val);
        var stock = (document.getElementById('sstock-' + slug) || {}).value || 0;
        appendVariant(val, '', stock);
    });
    document.querySelectorAll('#colour-chips .chip.active').forEach(function(chip) {
        var val   = chip.dataset.value;
        var slug  = slugify(val);
        var stock = (document.getElementById('cstock-' + slug) || {}).value || 0;
        appendVariant('', val, stock);
    });
});

// ── Custom select (dropdown) ──────────────────────────────
function csToggle(id) {
    var cs = document.getElementById(id);
    var trigger  = cs.querySelector('.cs-trigger');
    var dropdown = cs.querySelector('.cs-dropdown');
    var isOpen   = dropdown.classList.contains('open');
    document.querySelectorAll('.cs-dropdown.open').forEach(function(d) {
        d.classList.remove('open');
        d.closest('.custom-select').querySelector('.cs-trigger').classList.remove('open');
    });
    if (!isOpen) {
        dropdown.classList.add('open');
        trigger.classList.add('open');
        var search = dropdown.querySelector('.cs-search input');
        if (search) { search.value = ''; csSearch(id, ''); setTimeout(function() { search.focus(); }, 30); }
    }
}
function csSearch(id, query) {
    document.querySelectorAll('#' + id + ' .cs-option').forEach(function(o) {
        o.classList.toggle('hidden', query !== '' && o.textContent.toLowerCase().indexOf(query.toLowerCase()) === -1);
    });
}
function csClose(id) {
    var cs = document.getElementById(id);
    if (!cs) return;
    cs.querySelector('.cs-dropdown').classList.remove('open');
    cs.querySelector('.cs-trigger').classList.remove('open');
}
function csSelectCategory(val, displayText) {
    document.getElementById('category-display').textContent = displayText;
    document.querySelectorAll('#category-select .cs-option').forEach(function(o) {
        o.classList.toggle('selected', o.getAttribute('data-value') === val);
    });
    csClose('category-select');
    onCategorySelect(val);
}
function csSelectSupplier(val, displayText) {
    document.getElementById('f-supplier').value = val;
    document.getElementById('supplier-display').textContent = displayText;
    document.querySelectorAll('#supplier-select .cs-option').forEach(function(o) {
        o.classList.toggle('selected', String(o.getAttribute('data-value')) === String(val));
    });
    csClose('supplier-select');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-select')) {
        document.querySelectorAll('.cs-dropdown.open').forEach(function(d) {
            d.classList.remove('open');
            d.closest('.custom-select').querySelector('.cs-trigger').classList.remove('open');
        });
    }
});

// ── Helpers ─────────────────────────────────────────────
function slugify(str) {
    return String(str).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}
function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Gender pills ────────────────────────────────────────
function toggleGender(btn) {
    var current = document.getElementById('f-gender').value;
    var val     = btn.dataset.value;
    var isActive = current === val;

    // Deselect all pills
    document.querySelectorAll('.gender-pill').forEach(function(p) {
        p.style.background    = 'transparent';
        p.style.borderColor   = 'var(--border)';
        p.style.color         = 'var(--muted)';
    });

    if (isActive) {
        // Toggle off
        document.getElementById('f-gender').value = '';
    } else {
        // Select this one
        btn.style.background  = 'var(--terracotta)';
        btn.style.borderColor = 'var(--terracotta)';
        btn.style.color       = '#fff';
        document.getElementById('f-gender').value = val;
    }
}

// ── Boot ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    // Restore stock field opacity
    var trackCb = document.getElementById('f-track-stock');
    if (trackCb) toggleStockFields(trackCb.checked);

    // Sync category display on page load
    var catDisplay = document.getElementById('category-display');
    var hiddenCat  = document.getElementById('f-category');
    if (catDisplay && hiddenCat && hiddenCat.value && hiddenCat.value !== '__other__') {
        catDisplay.textContent = hiddenCat.value;
    }

    // Restore gender pill state on page load (edit mode / validation failure)
    var genderVal = document.getElementById('f-gender').value;
    if (genderVal) {
        var activeBtn = document.querySelector('.gender-pill[data-value="' + genderVal + '"]');
        if (activeBtn) {
            activeBtn.style.background  = 'var(--terracotta)';
            activeBtn.style.borderColor = 'var(--terracotta)';
            activeBtn.style.color       = '#fff';
        }
    }
});
</script>
@endsection
