@extends('layouts.staff')

@section('title', 'Sell')

@section('styles')
<style>

    /* ════════════════════════════════════════════════
       CATEGORY TABS
    ════════════════════════════════════════════════ */

    .cat-strip-outer {
        position: sticky;
        top: var(--top-h);
        z-index: 100;
        background: var(--darker-wood);
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .cat-strip {
        display: flex;
        align-items: center;
        gap: 4px;
        overflow-x: auto;
        padding: 8px 12px 10px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .cat-strip::-webkit-scrollbar { display: none; }
    .cat-tab {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        min-height: 44px;
        padding: 0 16px;
        border-radius: var(--radius-full);
        border: none;
        background: transparent;
        color: rgba(255,255,255,0.42);
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.14s, color 0.14s;
        -webkit-tap-highlight-color: transparent;
    }
    .cat-tab.active { background: #C17F4A; color: #fff; }
    .cat-tab:active { opacity: 0.78; }
    .cat-more-dropdown {
        display: none;
        position: absolute;
        top: calc(100% - 2px);
        right: 8px;
        background: #1A120B;
        border: 1px solid rgba(255,255,255,0.14);
        border-radius: 12px;
        padding: 6px 0;
        min-width: 160px;
        z-index: 110;
        box-shadow: 0 8px 28px rgba(0,0,0,0.45);
    }
    .cat-more-dropdown.open { display: block; }
    .cat-more-item {
        display: block; width: 100%;
        padding: 12px 18px;
        background: none; border: none;
        color: rgba(255,255,255,0.65);
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 14px; font-weight: 600;
        text-align: left; cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: background 0.1s;
    }
    .cat-more-item.active { color: #C17F4A; }
    .cat-more-item:active { background: rgba(255,255,255,0.07); }


    /* Mobile: hide overflow tabs, show More btn */
    .cat-overflow { display: none; }

    /* Tablet 768+: show all tabs (allow scroll), hide More btn */
    @media (min-width: 768px) {
        .cat-overflow { display: inline-flex !important; }
        #cat-more-btn { display: none !important; }
        .cat-more-dropdown { display: none !important; }
        .cat-strip { overflow-x: auto; }
    }

    /* Desktop 1024+: no scroll */
    @media (min-width: 1024px) {
        .cat-strip { overflow-x: visible; flex-wrap: wrap; gap: 6px; }
    }



    /* ════════════════════════════════════════════════
       PAGE
    ════════════════════════════════════════════════ */

    .staff-content { background: #FAF7F2; }
    .content-inner { background: #FAF7F2; overflow-x: hidden; }

    /* ════════════════════════════════════════════════
       PRODUCT GRID
    ════════════════════════════════════════════════ */

    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        width: 100%;
        box-sizing: border-box;
    }
    @media (min-width: 768px)  { .product-grid { grid-template-columns: repeat(3, 1fr); gap: 16px; } }
    @media (min-width: 1024px) { .product-grid { grid-template-columns: repeat(4, 1fr); gap: 16px; } }

    /* ════════════════════════════════════════════════
       PRODUCT CARD
    ════════════════════════════════════════════════ */

    .product-card {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
        min-height: 100px;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
        background: #FFFFFF;
        border: none;
        border-radius: var(--radius-default);
        box-shadow: 0 1px 3px rgba(28,24,20,0.07), 0 4px 12px rgba(28,24,20,0.05);
        cursor: pointer;
        text-align: left;
        transition: transform 0.12s ease, box-shadow 0.12s ease;
        -webkit-tap-highlight-color: transparent;
    }
    .product-card:active { transform: scale(0.97); }
    @media (hover: hover) {
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(28,24,20,0.1), 0 1px 3px rgba(28,24,20,0.07);
        }
    }
    .product-card.out-of-stock { opacity: 0.45; cursor: not-allowed; }
    .product-card.hidden { display: none; }

    .product-name {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #1C1814;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    .product-bottom {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 8px;
        margin-top: auto;
    }
    .product-price {
        font-family: "DM Mono", monospace;
        font-size: 16px;
        font-weight: 500;
        color: #C17F4A;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .bargain-dot { font-size: 7px; color: #C17F4A; opacity: 0.7; cursor: default; position: relative; top: -1px; }
    .product-stock {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 12px;
        font-weight: 500;
        color: #8C7B6E;
        white-space: nowrap;
        text-align: right;
        flex-shrink: 0;
    }
    .product-stock.stock-out { color: #B85C38; }
    .product-stock.stock-low { color: #A06020; }

    /* ════════════════════════════════════════════════
       NO RESULTS
    ════════════════════════════════════════════════ */

    .no-results {
        display: none;
        flex-direction: column;
        align-items: center;
        padding: 64px 24px;
        text-align: center;
        gap: 12px;
    }
    .no-results.visible { display: flex; }
    .no-results-icon    { color: #D9CEBC; }
    .no-results-text    { font-size: 15px; font-weight: 600; color: #8C7B6E; }
    .no-results-query   { font-size: 13px; color: #C4B9A8; }

    /* ════════════════════════════════════════════════
       SHARED SHEET BASE — overlay + sheet
    ════════════════════════════════════════════════ */

    .sheet-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(28,24,20,0.5);
        z-index: 900;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .sheet-overlay.open { display: block; opacity: 1; }
    .sheet-overlay.closing {
        opacity: 0;
        pointer-events: none;
    }

    .bottom-sheet {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #FFFFFF;
        border-radius: 20px 20px 0 0;
        max-height: 88vh;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 950;
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
        padding-bottom: env(safe-area-inset-bottom, 0px);
    }
    .bottom-sheet.open { transform: translateY(0); }

    @media (min-width: 768px) {
        .bottom-sheet {
            top: 50%; left: 50%; right: auto; bottom: auto;
            width: 480px; max-width: 90vw;
            border-radius: 16px; max-height: 85vh; padding-bottom: 0;
            transform: translate(-50%, -50%) scale(0.97);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }
        .bottom-sheet.open {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            pointer-events: auto;
        }
        .sheet-handle { display: none; }
    }

    .sheet-handle {
        width: 40px; height: 4px;
        background: #E8E0D6;
        border-radius: 2px;
        margin: 12px auto 0;
    }
    .sheet-header {
        padding: 16px 20px 14px;
        border-bottom: 1px solid #F0E8DC;
    }
    .sheet-product-name {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 17px; font-weight: 700; color: #1C1814;
        margin-bottom: 4px;
    }
    .sheet-product-meta {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13px; color: #8C7B6E;
    }
    .sheet-screen { padding: 20px 20px 28px; }
    .sheet-screen.hidden { display: none; }
    .section-label {
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.09em;
        color: #8C7B6E; margin-bottom: 10px; display: block;
    }

    /* ════════════════════════════════════════════════
       IN-SHEET MESSAGE BAR
    ════════════════════════════════════════════════ */

    .sheet-msg {
        margin: 0 20px 4px;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid transparent;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13px; font-weight: 500; line-height: 1.4;
    }

    /* ════════════════════════════════════════════════
       SALE MODAL — Screen 1: qty + price
    ════════════════════════════════════════════════ */

    .chip-row { display: flex; flex-wrap: wrap; gap: 8px; }
    .variant-section { margin-bottom: 20px; }

    .size-chip {
        min-width: 52px; height: 44px; padding: 0 14px;
        border-radius: 10px; border: 1.5px solid #E8E0D6;
        background: #FAF7F2;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 14px; font-weight: 600; color: #1C1814;
        cursor: pointer; transition: all 0.12s; position: relative;
        -webkit-tap-highlight-color: transparent;
    }
    .size-chip.active { border-color: #C17F4A; background: #FEF4E8; color: #C17F4A; }
    .size-chip.out { opacity: 0.4; cursor: not-allowed; }
    .size-chip .chip-stock {
        position: absolute; top: -5px; right: -5px;
        background: #E8E0D6; color: #8C7B6E;
        font-size: 9px; font-weight: 700; border-radius: 10px;
        padding: 1px 5px; font-family: "DM Mono", monospace;
    }
    .size-chip.active .chip-stock { background: #C17F4A; color: #fff; }

    .ml-chip {
        height: 44px; padding: 0 16px; border-radius: 10px;
        border: 1.5px solid #E8E0D6; background: #FAF7F2;
        font-family: "DM Mono", monospace; font-size: 14px; color: #1C1814;
        cursor: pointer; transition: all 0.12s;
        -webkit-tap-highlight-color: transparent;
    }
    .ml-chip.active { border-color: #4A6741; background: #EFF5EF; color: #4A6741; }

    .qty-section { margin-bottom: 20px; }
    .qty-row { display: flex; align-items: center; gap: 16px; }
    .qty-btn {
        width: 52px; height: 52px; border-radius: 14px;
        border: 1.5px solid #E8E0D6; background: #FAF7F2;
        font-size: 22px; color: #1C1814; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        -webkit-tap-highlight-color: transparent; transition: background 0.1s; flex-shrink: 0;
    }
    .qty-btn:active { background: #E8E0D6; }
    .qty-display {
        font-family: "DM Mono", monospace; font-size: 32px; font-weight: 500;
        color: #1C1814; min-width: 56px; text-align: center; flex: 1;
    }
    .qty-unit { font-size: 13px; color: #8C7B6E; font-family: "Plus Jakarta Sans", sans-serif; }

    .price-section { margin-bottom: 20px; }
    .price-row { display: flex; align-items: baseline; gap: 8px; }
    .price-prefix { font-family: "Plus Jakarta Sans", sans-serif; font-size: 13px; color: #8C7B6E; flex-shrink: 0; }
    .price-input {
        font-family: "DM Mono", monospace; font-size: 28px; font-weight: 500; color: #C17F4A;
        border: none; border-bottom: 2px solid #E8E0D6; background: transparent;
        outline: none; width: 100%; padding: 4px 0; transition: border-color 0.15s;
    }
    .price-input:focus { border-bottom-color: #C17F4A; }
    .price-input[readonly] { cursor: default; }
    .floor-hint { font-size: 11px; color: #8C7B6E; margin-top: 6px; }
    .floor-warn { font-size: 11px; color: #B85C38; margin-top: 6px; display: none; }

    .total-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 0 0; border-top: 1px solid #F0E8DC; margin-top: 4px;
    }
    .total-label { font-family: "Plus Jakarta Sans", sans-serif; font-size: 13px; font-weight: 600; color: #8C7B6E; }
    .total-amount { font-family: "DM Mono", monospace; font-size: 22px; font-weight: 500; color: #1C1814; }

    .btn-confirm {
        display: block; width: 100%; height: 56px; margin-top: 20px;
        background: #1C1814; color: #FAF7F2; border: none; border-radius: 14px;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 15px; font-weight: 700;
        cursor: pointer; letter-spacing: 0.02em;
        -webkit-tap-highlight-color: transparent; transition: opacity 0.12s;
    }
    .btn-confirm:active { opacity: 0.82; }
    .btn-confirm:disabled { opacity: 0.35; cursor: not-allowed; }

    /* ════════════════════════════════════════════════
       CART REVIEW SHEET
    ════════════════════════════════════════════════ */

    .cart-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #8C7B6E;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 15px;
        font-weight: 500;
    }
    .cart-empty-icon { color: #D9CEBC; margin-bottom: 12px; }

    .cart-items-list { padding: 0; }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 0;
        border-bottom: 1px solid #F5F0E8;
    }
    .cart-item:last-child { border-bottom: none; }

    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-name {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 14px; font-weight: 600; color: #1C1814;
        display: block;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .cart-item-variant {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 12px; color: #8C7B6E; display: block; margin-top: 2px;
    }

    .cart-qty-controls { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .qty-btn-sm {
        width: 32px; height: 32px; border-radius: 8px;
        border: 1.5px solid #E8E0D6; background: #FAF7F2;
        font-size: 18px; color: #1C1814; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        -webkit-tap-highlight-color: transparent; flex-shrink: 0;
        transition: background 0.1s;
    }
    .qty-btn-sm:active { background: #E8E0D6; }
    .cart-qty-num {
        font-family: "DM Mono", monospace; font-size: 16px; font-weight: 500;
        color: #1C1814; min-width: 24px; text-align: center;
    }

    .cart-item-total {
        font-family: "DM Mono", monospace; font-size: 14px; font-weight: 500;
        color: #C17F4A; flex-shrink: 0; text-align: right; min-width: 72px;
    }

    .cart-remove-btn {
        width: 36px; height: 44px; border: none; background: none;
        color: #C4B9A8; font-size: 20px; cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        -webkit-tap-highlight-color: transparent; border-radius: 8px;
        transition: color 0.12s;
    }
    .cart-remove-btn:active { color: #B85C38; }

    .cart-total-row {
        display: flex; justify-content: space-between; align-items: baseline;
        padding: 16px 0 0; border-top: 2px solid #F0E8DC; margin-top: 8px;
    }
    .cart-total-label { font-family: "Plus Jakarta Sans", sans-serif; font-size: 14px; font-weight: 700; color: #1C1814; }
    .cart-total-amount { font-family: "DM Mono", monospace; font-size: 24px; font-weight: 500; color: #1C1814; }

    .btn-continue {
        display: block; width: 100%; padding: 14px;
        text-align: center; background: none; border: none;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 14px;
        font-weight: 600; color: #8C7B6E; cursor: pointer; margin-top: 8px;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-continue:active { color: #C17F4A; }

    /* ════════════════════════════════════════════════
       CHECKOUT SCREEN
    ════════════════════════════════════════════════ */

    .pay-summary {
        background: #FAF7F2; border-radius: 12px;
        padding: 14px 16px; margin-bottom: 20px; text-align: center;
    }
    .pay-product { font-family: "Plus Jakarta Sans", sans-serif; font-size: 13px; color: #8C7B6E; margin-bottom: 4px; }
    .pay-total   { font-family: "DM Mono", monospace; font-size: 28px; font-weight: 500; color: #1C1814; }

    .pay-btn {
        display: block; width: 100%; height: 64px; margin-bottom: 10px;
        border: none; border-radius: 14px;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 16px; font-weight: 700;
        cursor: pointer; letter-spacing: 0.01em;
        -webkit-tap-highlight-color: transparent; transition: opacity 0.12s;
    }
    .pay-btn:active { opacity: 0.82; }
    .pay-btn.cash   { background: #1C1814; color: #FAF7F2; }
    .pay-btn.mpesa  { background: #4A6741; color: #fff; }
    .pay-btn.credit { background: #C17F4A; color: #fff; font-size: 14px; }

    .credit-fields {
        display: none; flex-direction: column; gap: 10px;
        margin-top: 12px; padding-top: 16px; border-top: 1px solid #F0E8DC;
    }
    .credit-fields.open { display: flex; }

    .credit-input {
        width: 100%; height: 52px; padding: 0 14px;
        border: 1.5px solid #E8E0D6; border-radius: 12px;
        background: #FAF7F2;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 15px; color: #1C1814;
        outline: none; box-sizing: border-box; transition: border-color 0.15s;
    }
    .credit-input:focus { border-color: #C17F4A; }
    .credit-input::placeholder { color: #B8A898; }

    .credit-partial-wrap {
        background: #FAF7F2; border-radius: 12px;
        padding: 14px; display: flex; flex-direction: column; gap: 10px;
    }
    .credit-partial-row { display: flex; align-items: baseline; gap: 8px; }
    .credit-partial-prefix { font-family: "Plus Jakarta Sans", sans-serif; font-size: 13px; color: #8C7B6E; flex-shrink: 0; }
    .credit-partial-input {
        font-family: "DM Mono", monospace; font-size: 20px; font-weight: 500; color: #1C1814;
        border: none; border-bottom: 1.5px solid #E8E0D6; background: transparent;
        outline: none; width: 100%; padding: 4px 0; transition: border-color 0.15s;
    }
    .credit-partial-input:focus { border-bottom-color: #C17F4A; }
    .credit-balance-row { display: flex; justify-content: space-between; align-items: center; }
    .credit-balance-label { font-family: "Plus Jakarta Sans", sans-serif; font-size: 13px; color: #8C7B6E; }
    .credit-balance-amount { font-family: "DM Mono", monospace; font-size: 16px; font-weight: 500; color: #C17F4A; }
    .credit-full-warn {
        font-size: 12px; color: #B85C38;
        padding: 6px 10px; background: #FDF0EC; border-radius: 8px; display: none;
    }

    .btn-confirm-credit {
        display: block; width: 100%; height: 56px;
        background: #C17F4A; color: #fff; border: none; border-radius: 14px;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 15px; font-weight: 700;
        cursor: pointer; -webkit-tap-highlight-color: transparent; transition: opacity 0.12s;
        margin-top: 4px;
    }
    .btn-confirm-credit:active { opacity: 0.82; }
    .btn-confirm-credit:disabled { opacity: 0.35; cursor: not-allowed; }

    .sheet-back {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 0 0; background: none; border: none;
        color: #8C7B6E; font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13px; font-weight: 600; cursor: pointer;
        margin-bottom: 16px; -webkit-tap-highlight-color: transparent;
    }
    .sheet-back:active { color: #C17F4A; }

    .customer-found-pill {
        display: none;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #EFF5EF;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #4A6741;
    }
    .customer-found-pill.visible { display: flex; }

    /* ════════════════════════════════════════════════
       TOAST
    ════════════════════════════════════════════════ */

    .sale-toast {
        position: fixed;
        bottom: calc(var(--tab-h) + 20px);
        left: 50%; transform: translateX(-50%) translateY(20px);
        background: #1C1814; color: #FAF7F2;
        font-family: "Plus Jakarta Sans", sans-serif; font-size: 14px; font-weight: 600;
        padding: 12px 20px; border-radius: 100px; white-space: nowrap;
        z-index: 1001; opacity: 0;
        transition: opacity 0.2s ease, transform 0.2s ease;
        pointer-events: none;
        max-width: calc(100vw - 40px); overflow: hidden; text-overflow: ellipsis;
    }
    .sale-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

</style>
@endsection

@section('above-content')
<div class="cat-strip-outer">
    <div class="cat-strip" id="cat-strip"></div>
</div>
@endsection

@section('content')
{{-- Section label --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin:20px 0 14px;">
    <span style="font-size:11px;font-weight:700;color:#8C7B6E;text-transform:uppercase;letter-spacing:0.1em;">All Products</span>
    <span id="product-count" style="font-family:'DM Mono',monospace;font-size:11px;color:#8C7B6E;">{{ $products->count() }} items</span>
</div>

{{-- Product grid --}}
<div class="product-grid" id="product-grid">

@foreach($products as $product)
@php
    $isVariant  = $product->type === 'variant';
    $isMeasured = $product->type === 'measured';
    $bottle     = $isMeasured ? $product->bottles->first() : null;

    if ($isVariant)       { $totalStock = $product->variants->sum('stock'); }
    elseif ($isMeasured)  { $totalStock = $bottle ? (int) $bottle->remaining_ml : 0; }
    else                  { $totalStock = $product->stock; }

    $isOut  = $totalStock <= 0;
    $isLow  = !$isOut && $totalStock <= $product->low_stock_threshold;

    if ($isVariant)      { $stockLabel = $isOut ? 'Out of stock' : $totalStock . ' in stock'; }
    elseif ($isMeasured) { $remaining = $bottle ? number_format($bottle->remaining_ml, 0) : 0; $stockLabel = $isOut ? 'Empty' : $remaining . 'ml left'; }
    else                 { $stockLabel = $isOut ? 'Out of stock' : $totalStock . ' in stock'; }

    $stockCss   = $isOut ? 'stock-out' : ($isLow ? 'stock-low' : '');
    $priceLabel = $isMeasured ? 'Ksh ' . number_format($product->shelf_price, 0) . '/ml' : 'Ksh ' . number_format($product->shelf_price, 0);

    $variantsJson = $isVariant
        ? json_encode($product->variants->map(fn($v) => ['id' => $v->id, 'size' => $v->size, 'colour' => $v->colour, 'stock' => $v->stock])->values()->all())
        : '[]';

    $bottleJson = ($isMeasured && $bottle)
        ? json_encode(['id' => $bottle->id, 'remaining_ml' => (float)$bottle->remaining_ml, 'price_per_ml' => (float)$bottle->price_per_ml])
        : 'null';
@endphp

<button
    class="product-card{{ $isOut ? ' out-of-stock' : '' }}"
    data-id="{{ $product->id }}"
    data-type="{{ $product->type }}"
    data-name="{{ $product->name }}"
    data-price="{{ $product->shelf_price }}"
    data-floor="{{ $product->floor_price ?? '' }}"
    data-bargainable="{{ $product->is_bargainable ? '1' : '0' }}"
    data-stock="{{ $totalStock }}"
    data-threshold="{{ $product->low_stock_threshold }}"
    data-variants="{{ $variantsJson }}"
    data-bottle="{{ $bottleJson }}"
    data-search="{{ strtolower($product->name . ' ' . $product->category) }}"
    data-category="{{ $product->category }}"
    onclick="openSheet(this)"
>
    <span class="product-name">{{ $product->name }}</span>
    <div class="product-bottom">
        <span class="product-price">
            {{ $priceLabel }}
            @if($product->is_bargainable && $product->floor_price)
                <span class="bargain-dot" title="Price is negotiable">&#9670;</span>
            @endif
        </span>
        <span class="product-stock {{ $stockCss }}" id="stock-{{ $product->id }}">{{ $stockLabel }}</span>
    </div>
</button>

@endforeach
</div>

{{-- No results --}}
<div class="no-results" id="no-results">
    <svg class="no-results-icon" width="44" height="44" viewBox="0 0 44 44" fill="none">
        <circle cx="20" cy="20" r="12" stroke="currentColor" stroke-width="1.8"/>
        <path d="M29 29L39 39" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M14.5 20h11M20 14.5v11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" opacity="0.35"/>
    </svg>
    <span class="no-results-text">No products found</span>
    <span class="no-results-query" id="no-results-query"></span>
</div>

<div style="height:32px;"></div>

{{-- ══════════════════════ SALE MODAL ══════════════════════ --}}

<div class="sheet-overlay" id="sheet-overlay" onclick="closeSheet()"></div>

<div class="bottom-sheet" id="bottom-sheet">
    <div class="sheet-handle"></div>
    <div class="sheet-header">
        <div class="sheet-product-name" id="sheet-name"></div>
        <div class="sheet-product-meta" id="sheet-meta"></div>
    </div>
    <div class="sheet-msg" id="sheet-msg" style="display:none;"></div>

    {{-- Screen 1: qty + price → Add to Cart --}}
    <div class="sheet-screen" id="screen-confirm">

        <div class="variant-section" id="variant-section" style="display:none;">
            <span class="section-label">Select Size</span>
            <div class="chip-row" id="chip-row"></div>
        </div>

        <div class="variant-section" id="ml-section" style="display:none;">
            <span class="section-label">Select Amount</span>
            <div class="chip-row" id="ml-chip-row">
                <button type="button" class="ml-chip" data-ml="5" >5ml</button>
                <button type="button" class="ml-chip" data-ml="10">10ml</button>
                <button type="button" class="ml-chip" data-ml="20">20ml</button>
                <button type="button" class="ml-chip" data-ml="30">30ml</button>
                <button type="button" class="ml-chip" data-ml="50">50ml</button>
            </div>
        </div>

        <div class="qty-section" id="qty-section">
            <span class="section-label">Quantity</span>
            <div class="qty-row">
                <button type="button" class="qty-btn qty-minus">&#8722;</button>
                <span class="qty-display" id="qty-display">1</span>
                <span class="qty-unit">pcs</span>
                <button type="button" class="qty-btn qty-plus">+</button>
            </div>
        </div>

        <div class="price-section">
            <span class="section-label">Price</span>
            <div class="price-row">
                <span class="price-prefix">Ksh</span>
                <input type="number" class="price-input" id="price-input" inputmode="decimal" step="1" min="0">
            </div>
            <div class="floor-hint" id="floor-hint"></div>
            <div class="floor-warn" id="floor-warn">&#9888; Below floor price</div>
        </div>

        <div class="total-row">
            <span class="total-label">Item total</span>
            <span class="total-amount" id="running-total">Ksh 0</span>
        </div>

        <button type="button" class="btn-confirm" id="btn-add-to-cart" onclick="doAddToCart()">
            Add to Cart
        </button>
    </div>
</div>

{{-- ══════════════════════ CART SHEET ══════════════════════ --}}

<div class="sheet-overlay" id="cart-overlay" onclick="closeCartSheet()"></div>

<div class="bottom-sheet" id="cart-sheet">
    <div class="sheet-handle"></div>
    <div class="sheet-header">
        <div class="sheet-product-name" id="cart-title">Cart</div>
        <div class="sheet-product-meta" id="cart-subtitle"></div>
    </div>

    {{-- Cart items screen --}}
    <div class="sheet-screen" id="cart-screen-items">
        <div class="cart-items-list" id="cart-items-list"></div>
        <div class="cart-total-row" id="cart-total-row" style="display:none;">
            <span class="cart-total-label">Total</span>
            <span class="cart-total-amount" id="cart-grand-total">Ksh 0</span>
        </div>
        <button type="button" class="btn-confirm" id="btn-checkout"
                onclick="goToCheckout()" style="margin-top:16px;">
            Checkout &#8594;
        </button>
        <button type="button" class="btn-continue" onclick="closeCartSheet()">
            Continue shopping
        </button>
    </div>

    {{-- Checkout screen --}}
    <div class="sheet-screen hidden" id="cart-screen-checkout">
        <button type="button" class="sheet-back" onclick="backToCartItems()">&#8592; Back</button>

        <div class="pay-summary">
            <div class="pay-product" id="checkout-summary"></div>
            <div class="pay-total" id="checkout-total"></div>
        </div>

        <span class="section-label">How are they paying?</span>

        <button type="button" class="pay-btn cash"   onclick="selectCartPayment('cash')">Cash</button>
        <button type="button" class="pay-btn mpesa"  onclick="selectCartPayment('mpesa')">M-Pesa</button>
        <button type="button" class="pay-btn credit" onclick="selectCartPayment('credit')">Credit &mdash; Kulipa Baadaye</button>

        <div class="credit-fields" id="cart-credit-fields">
            <input type="tel"  class="credit-input" id="cart-credit-phone"
                   placeholder="Phone number (07xx) *"
                   oninput="lookupCustomer(this.value)" autocomplete="off">
            <div class="customer-found-pill" id="customer-found-pill">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.4"/>
                    <path d="M3.5 6l1.7 1.7L8.5 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span id="customer-found-name">Customer found</span>
            </div>
            <input type="text" class="credit-input" id="cart-credit-name"
                   placeholder="Customer name *" autocomplete="off">

            <div class="credit-partial-wrap">
                <span class="section-label" style="margin-bottom:0;">
                    Amount paying now
                    <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span>
                </span>
                <div class="credit-partial-row">
                    <span class="credit-partial-prefix">Ksh</span>
                    <input type="number" class="credit-partial-input" id="cart-credit-paid-now"
                           inputmode="decimal" step="1" min="0" placeholder="0"
                           oninput="updateCreditBalance()">
                </div>
                <div class="credit-balance-row">
                    <span class="credit-balance-label">Credit balance:</span>
                    <span class="credit-balance-amount" id="cart-credit-balance">&#8212;</span>
                </div>
                <div class="credit-full-warn" id="cart-credit-full-warn">
                    Full amount entered &#8212; use Cash or M-Pesa instead
                </div>
            </div>

            <button type="button" class="btn-confirm-credit" id="btn-confirm-cart-credit"
                    onclick="confirmCartSale('credit')">
                Record Credit Sale
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="sale-toast" id="sale-toast"></div>

@endsection

@section('scripts')
<script>
(function () {

    var CART_KEY = 'stoka_cart';

    /* ══════════════════════════════════════════════
       SEARCH + CATEGORY FILTER
    ══════════════════════════════════════════════ */

    var searchInput = document.getElementById('staff-search');
    var grid        = document.getElementById('product-grid');
    var countEl     = document.getElementById('product-count');
    var noResults   = document.getElementById('no-results');
    var noQuery     = document.getElementById('no-results-query');
    var catStrip    = document.getElementById('cat-strip');
    var cards       = Array.prototype.slice.call(grid.querySelectorAll('.product-card'));
    var total       = cards.length;
    var activeCat   = '';

    // Count products per category, rank by count
    var catCounts = {};
    cards.forEach(function (c) {
        var cat = c.dataset.category || '';
        if (cat) catCounts[cat] = (catCounts[cat] || 0) + 1;
    });
    var sortedCats  = Object.keys(catCounts).sort(function (a, b) { return catCounts[b] - catCounts[a]; });
    var primaryCats = sortedCats.slice(0, 4);
    var moreCats    = sortedCats.slice(4);
    var moreDropdown = null;

    function makeTab(label, value) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'cat-tab' + (value === activeCat ? ' active' : '');
        btn.dataset.cat = value;
        btn.textContent = label;
        btn.addEventListener('click', function () {
            activeCat = value;
            catStrip.querySelectorAll('.cat-tab').forEach(function (t) {
                t.classList.toggle('active', t.dataset.cat === value);
            });
            if (moreDropdown) {
                moreDropdown.classList.remove('open');
                moreDropdown.querySelectorAll('.cat-more-item').forEach(function (i) {
                    i.classList.toggle('active', i.dataset.cat === value);
                });
            }
            applyFilters();
        });
        return btn;
    }

    catStrip.appendChild(makeTab('All', ''));
    primaryCats.forEach(function (cat) { catStrip.appendChild(makeTab(cat, cat)); });

    // Overflow tabs — hidden on mobile via CSS, visible on tablet/desktop
    moreCats.forEach(function (cat) {
        var btn = makeTab(cat, cat);
        btn.classList.add('cat-overflow');
        catStrip.appendChild(btn);
    });

    // "More ▾" button + dropdown — only used on mobile (hidden via CSS on tablet+)
    if (moreCats.length > 0) {
        var moreBtn = document.createElement('button');
        moreBtn.type = 'button';
        moreBtn.className = 'cat-tab';
        moreBtn.id = 'cat-more-btn';
        moreBtn.dataset.cat = '__more__';
        moreBtn.textContent = 'More ▾';
        catStrip.appendChild(moreBtn);

        moreDropdown = document.createElement('div');
        moreDropdown.className = 'cat-more-dropdown';
        moreDropdown.id = 'cat-more-dropdown';
        moreCats.forEach(function (cat) {
            var item = document.createElement('button');
            item.type = 'button';
            item.className = 'cat-more-item';
            item.dataset.cat = cat;
            item.textContent = cat;
            item.addEventListener('click', function (e) {
                e.stopPropagation();
                activeCat = cat;
                // Highlight the overflow tab in the strip
                catStrip.querySelectorAll('.cat-tab').forEach(function (t) {
                    t.classList.toggle('active', t.dataset.cat === cat);
                });
                // Also highlight More btn on mobile (overflow tab is hidden)
                moreBtn.classList.add('active');
                moreDropdown.querySelectorAll('.cat-more-item').forEach(function (i) {
                    i.classList.toggle('active', i.dataset.cat === cat);
                });
                moreDropdown.classList.remove('open');
                applyFilters();
            });
            moreDropdown.appendChild(item);
        });
        document.querySelector('.cat-strip-outer').appendChild(moreDropdown);

        moreBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            moreDropdown.classList.toggle('open');
        });
        document.addEventListener('click', function () { moreDropdown.classList.remove('open'); });
    }

    if (sortedCats.length === 0) catStrip.closest('.cat-strip-outer').style.display = 'none';

    function applyFilters() {
        var q = searchInput.value.trim().toLowerCase();
        var visible = 0;
        cards.forEach(function (card) {
            var ok = (!q || card.dataset.search.indexOf(q) !== -1)
                  && (!activeCat || card.dataset.category === activeCat);
            card.classList.toggle('hidden', !ok);
            if (ok) visible++;
        });
        var isFiltered = q || activeCat;
        countEl.textContent = isFiltered
            ? visible + (visible === 1 ? ' result' : ' results')
            : total + ' items';
        if (visible === 0 && isFiltered) {
            noResults.classList.add('visible');
            noQuery.textContent = q ? '\u201C' + searchInput.value.trim() + '\u201D' : '';
        } else {
            noResults.classList.remove('visible');
            noQuery.textContent = '';
        }
    }
    searchInput.addEventListener('input', applyFilters);

    /* ══════════════════════════════════════════════
       CART MANAGEMENT
    ══════════════════════════════════════════════ */

    function getCart() {
        try { return JSON.parse(sessionStorage.getItem(CART_KEY) || '[]'); }
        catch (e) { return []; }
    }

    function saveCart(items) {
        sessionStorage.setItem(CART_KEY, JSON.stringify(items));
        window.updateCartBadge();
    }

    window.updateCartBadge = function () {
        var cart  = getCart();
        var count = cart.reduce(function (s, i) { return s + (i.quantity || 1); }, 0);
        var badge = document.getElementById('cart-badge');
        var btn   = document.getElementById('cart-btn');
        if (!badge) return;
        if (count > 0) {
            badge.textContent  = count;
            badge.style.display = 'flex';
            if (btn) btn.classList.add('has-items');
        } else {
            badge.style.display = 'none';
            if (btn) btn.classList.remove('has-items');
        }
    };

    window.updateCartBadge(); // init on page load

    function cartTotal() {
        return getCart().reduce(function (s, i) { return s + i.actual_price * i.quantity; }, 0);
    }

    /* ── Add to cart ─────────────────────────────── */
    window.doAddToCart = function () {
        var label = '';
        if (state.productType === 'variant' && state.variantId) {
            var v = state.variants.find(function (x) { return x.id === state.variantId; });
            label = v ? (v.size + (v.colour ? ' / ' + v.colour : '')) : '';
        } else if (state.productType === 'measured') {
            label = state.qty + 'ml';
        }

        var newItem = {
            product_id:    state.productId,
            product_type:  state.productType,
            name:          state.productName,
            variant_id:    state.variantId,
            bottle_id:     (state.productType === 'measured' && state.bottle) ? state.bottle.id : null,
            variant_label: label || null,
            quantity:      state.qty,
            unit_price:    state.shelfPrice,
            actual_price:  state.price,
            is_bargainable: state.bargainable,
            floor_price:   state.floorPrice,
        };

        var cart     = getCart();
        var existing = cart.find(function (i) {
            return i.product_id === newItem.product_id && i.variant_id === newItem.variant_id;
        });
        if (existing) {
            existing.quantity += newItem.quantity;
        } else {
            cart.push(newItem);
        }
        saveCart(cart);

        var addedLabel = state.productName + (label ? ' (' + label + ')' : '');
        closeSheet();
        showToast('\u2713 ' + addedLabel + ' added');
    };

    /* ══════════════════════════════════════════════
       SALE MODAL STATE
    ══════════════════════════════════════════════ */

    var overlay    = document.getElementById('sheet-overlay');
    var sheet      = document.getElementById('bottom-sheet');
    var scrConfirm = document.getElementById('screen-confirm');

    var state = {
        productId: null, productType: null, productName: null,
        shelfPrice: 0, floorPrice: null, bargainable: false,
        variantId: null, variants: [], bottle: null, qty: 1, price: 0,
    };

    window.openSheet = function (btn) {
        var isOutOfStock = btn.classList.contains('out-of-stock');

        state.productId   = parseInt(btn.dataset.id);
        state.productType = btn.dataset.type;
        state.productName = btn.dataset.name;
        state.shelfPrice  = parseFloat(btn.dataset.price);
        state.floorPrice  = btn.dataset.floor ? parseFloat(btn.dataset.floor) : null;
        state.bargainable = btn.dataset.bargainable === '1';

        try { state.variants = JSON.parse(btn.dataset.variants || '[]'); } catch (e) { state.variants = []; }
        try { state.bottle   = JSON.parse(btn.dataset.bottle   || 'null'); } catch (e) { state.bottle = null; }

        state.variantId = null;
        state.qty       = 1;
        state.price     = state.shelfPrice;

        document.getElementById('sheet-name').textContent = state.productName;

        var meta = '';
        if (state.productType === 'measured' && state.bottle) {
            meta = 'Ksh ' + nf(state.bottle.price_per_ml) + '/ml \u00b7 ' + nf(state.bottle.remaining_ml) + 'ml remaining';
        } else if (state.productType === 'variant') {
            meta = 'Choose a size below';
        } else {
            meta = 'Ksh ' + nf(state.shelfPrice) + (state.bargainable ? ' \u00b7 Bargainable' : '');
        }
        document.getElementById('sheet-meta').textContent = meta;

        document.getElementById('variant-section').style.display = state.productType === 'variant'  ? '' : 'none';
        document.getElementById('ml-section').style.display      = state.productType === 'measured' ? '' : 'none';
        document.getElementById('qty-section').style.display     = state.productType === 'measured' ? 'none' : '';

        if (state.productType === 'variant') buildChips();

        if (state.productType === 'measured') {
            document.querySelectorAll('.ml-chip').forEach(function (c) { c.classList.remove('active'); });
            state.qty   = 0;
            state.price = state.bottle ? state.bottle.price_per_ml : 0;
        }

        var pi      = document.getElementById('price-input');
        pi.readOnly = !state.bargainable || state.productType === 'measured';
        pi.value    = state.price > 0 ? state.price : '';

        document.getElementById('floor-hint').textContent =
            (state.bargainable && state.floorPrice)
                ? 'Floor price: Ksh ' + nf(state.floorPrice) : '';
        document.getElementById('qty-display').textContent = state.productType === 'measured' ? '0ml' : '1';

        updateFloorWarn();
        updateTotal();

        scrConfirm.classList.remove('hidden');

        overlay.classList.add('open');
        requestAnimationFrame(function () { sheet.classList.add('open'); });
        document.body.style.overflow = 'hidden';

        hideMsg();
        if (isOutOfStock) {
            showMsg('This item is currently out of stock.', 'warn');
            document.getElementById('btn-add-to-cart').disabled    = true;
            document.getElementById('btn-add-to-cart').textContent = 'Out of Stock';
        } else {
            document.getElementById('btn-add-to-cart').textContent = 'Add to Cart';
            validateAddBtn();
        }
    };

    function buildChips() {
        var row = document.getElementById('chip-row');
        row.innerHTML = '';
        state.variants.forEach(function (v) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'size-chip' + (v.stock <= 0 ? ' out' : '');
            btn.dataset.variantId = v.id;
            btn.innerHTML = esc(v.size + (v.colour ? ' / ' + v.colour : ''))
                          + '<span class="chip-stock">' + v.stock + '</span>';
            row.appendChild(btn);
        });
    }

    /* ── Sale sheet event delegation ─────────────── */
    document.getElementById('bottom-sheet').addEventListener('click', function (e) {
        var tgt = e.target;

        if (tgt.closest('.qty-plus')) {
            state.qty++;
            document.getElementById('qty-display').textContent = state.qty;
            updateTotal(); validateAddBtn(); return;
        }
        if (tgt.closest('.qty-minus')) {
            state.qty = Math.max(1, state.qty - 1);
            document.getElementById('qty-display').textContent = state.qty;
            updateTotal(); validateAddBtn(); return;
        }
        var sChip = tgt.closest('.size-chip');
        if (sChip && !sChip.classList.contains('out')) {
            document.querySelectorAll('.size-chip').forEach(function (ch) { ch.classList.remove('active'); });
            sChip.classList.add('active');
            state.variantId = parseInt(sChip.dataset.variantId);
            updateTotal(); validateAddBtn(); return;
        }
        var mChip = tgt.closest('.ml-chip');
        if (mChip) {
            document.querySelectorAll('.ml-chip').forEach(function (ch) { ch.classList.remove('active'); });
            mChip.classList.add('active');
            state.qty   = parseFloat(mChip.dataset.ml);
            state.price = state.bottle ? state.bottle.price_per_ml : 0;
            document.getElementById('qty-display').textContent = state.qty + 'ml';
            document.getElementById('price-input').value       = state.price;
            updateTotal(); validateAddBtn(); return;
        }
    });

    document.getElementById('price-input').addEventListener('input', function () {
        state.price = parseFloat(this.value) || 0;
        updateFloorWarn();
        updateTotal();
        validateAddBtn();
    });

    function updateFloorWarn() {
        var warn = document.getElementById('floor-warn');
        var show = state.bargainable && state.floorPrice && state.price > 0 && state.price < state.floorPrice;
        warn.style.display = show ? 'block' : 'none';
    }

    function updateTotal() {
        document.getElementById('running-total').textContent = 'Ksh ' + nf(state.qty * state.price);
    }

    function validateAddBtn() {
        var ok = true;
        if (state.productType === 'variant' && !state.variantId)  ok = false;
        if (state.productType === 'measured' && state.qty <= 0)   ok = false;
        if (state.price <= 0) ok = false;
        var btn = document.getElementById('btn-add-to-cart');
        if (btn && !btn.classList.contains('oos')) btn.disabled = !ok;
    }

    window.closeSheet = function () {
        sheet.classList.remove('open');
        overlay.classList.add('closing');
        setTimeout(function () {
            overlay.classList.remove('open', 'closing');
        }, 260);
        document.body.style.overflow = '';
    };

    /* ══════════════════════════════════════════════
       CART SHEET
    ══════════════════════════════════════════════ */

    var cartOverlay = document.getElementById('cart-overlay');
    var cartSheet   = document.getElementById('cart-sheet');
    var cartScItems = document.getElementById('cart-screen-items');
    var cartScCheck = document.getElementById('cart-screen-checkout');

    window.openCartSheet = function () {
        var cart = getCart();
        renderCartItems(cart);

        cartScItems.classList.remove('hidden');
        cartScCheck.classList.add('hidden');

        cartOverlay.classList.add('open');
        requestAnimationFrame(function () { cartSheet.classList.add('open'); });
        document.body.style.overflow = 'hidden';
    };

    window.closeCartSheet = function () {
        cartSheet.classList.remove('open');
        cartOverlay.classList.add('closing');
        setTimeout(function () {
            cartOverlay.classList.remove('open', 'closing');
        }, 260);
        document.body.style.overflow = '';
    };

    function renderCartItems(cart) {
        cart = cart || getCart();
        var list    = document.getElementById('cart-items-list');
        var total   = 0;
        var itemCnt = 0;
        list.innerHTML = '';

        if (cart.length === 0) {
            list.innerHTML = '<div class="cart-empty-state">'
                + '<div class="cart-empty-icon"><svg width="40" height="40" viewBox="0 0 40 40" fill="none">'
                + '<path d="M13 16.5V13a7 7 0 0 1 14 0v3.5" stroke="#D9CEBC" stroke-width="1.8" stroke-linecap="round"/>'
                + '<rect x="5" y="16.5" width="30" height="19" rx="3" stroke="#D9CEBC" stroke-width="1.8"/>'
                + '</svg></div>'
                + '<div>Cart is empty</div></div>';
            document.getElementById('cart-total-row').style.display = 'none';
            document.getElementById('btn-checkout').style.display = 'none';
            return;
        }

        document.getElementById('cart-total-row').style.display = 'flex';
        document.getElementById('btn-checkout').style.display   = 'block';

        cart.forEach(function (item, idx) {
            var line = item.actual_price * item.quantity;
            total   += line;
            itemCnt += item.quantity;

            var el = document.createElement('div');
            el.className = 'cart-item';

            el.innerHTML =
                '<div class="cart-item-info">'
                +   '<span class="cart-item-name">' + esc(item.name) + '</span>'
                +   (item.variant_label ? '<span class="cart-item-variant">' + esc(item.variant_label) + '</span>' : '')
                + '</div>'
                + '<div class="cart-qty-controls">'
                +   '<button type="button" class="qty-btn-sm" onclick="cartQty(' + idx + ',-1)">&#8722;</button>'
                +   '<span class="cart-qty-num">' + item.quantity + '</span>'
                +   '<button type="button" class="qty-btn-sm" onclick="cartQty(' + idx + ',1)">+</button>'
                + '</div>'
                + '<span class="cart-item-total">Ksh ' + nf(line) + '</span>'
                + '<button type="button" class="cart-remove-btn" onclick="cartRemove(' + idx + ')">&times;</button>';

            list.appendChild(el);
        });

        var label = itemCnt + (itemCnt === 1 ? ' item' : ' items');
        document.getElementById('cart-title').textContent    = 'Cart \u00b7 ' + label;
        document.getElementById('cart-grand-total').textContent = 'Ksh ' + nf(total);
    }

    window.cartQty = function (idx, delta) {
        var cart = getCart();
        cart[idx].quantity = Math.max(1, cart[idx].quantity + delta);
        saveCart(cart);
        renderCartItems(cart);
    };

    window.cartRemove = function (idx) {
        var cart = getCart();
        cart.splice(idx, 1);
        saveCart(cart);
        renderCartItems(cart);
        if (cart.length === 0) {
            setTimeout(closeCartSheet, 600);
        }
    };

    /* ── Checkout screen ──────────────────────────── */

    window.goToCheckout = function () {
        var cart    = getCart();
        var total   = cartTotal();
        var itemCnt = cart.reduce(function (s, i) { return s + i.quantity; }, 0);

        document.getElementById('checkout-summary').textContent =
            itemCnt + (itemCnt === 1 ? ' item' : ' items');
        document.getElementById('checkout-total').textContent   = 'Ksh ' + nf(total);

        // Reset credit fields
        document.getElementById('cart-credit-fields').classList.remove('open');
        document.getElementById('cart-credit-phone').value    = '';
        document.getElementById('cart-credit-name').value     = '';
        document.getElementById('cart-credit-paid-now').value = '';
        document.getElementById('cart-credit-balance').textContent = '\u2014';
        document.getElementById('cart-credit-full-warn').style.display = 'none';
        document.getElementById('btn-confirm-cart-credit').disabled = false;
        document.getElementById('customer-found-pill').classList.remove('visible');

        cartScItems.classList.add('hidden');
        cartScCheck.classList.remove('hidden');
    };

    window.backToCartItems = function () {
        cartScCheck.classList.add('hidden');
        cartScItems.classList.remove('hidden');
    };

    window.selectCartPayment = function (type) {
        if (type === 'credit') {
            var total = cartTotal();
            document.getElementById('cart-credit-balance').textContent = 'Ksh ' + nf(total);
            document.getElementById('cart-credit-fields').classList.add('open');
            return;
        }
        confirmCartSale(type);
    };

    /* ── Credit balance live update ──────────────── */
    window.updateCreditBalance = function () {
        var total    = cartTotal();
        var paidNow  = parseFloat(document.getElementById('cart-credit-paid-now').value) || 0;
        var balance  = total - paidNow;
        var fullWarn = document.getElementById('cart-credit-full-warn');
        var submitBtn= document.getElementById('btn-confirm-cart-credit');
        var balEl    = document.getElementById('cart-credit-balance');

        if (paidNow > total) {
            document.getElementById('cart-credit-paid-now').value = total;
            paidNow = total; balance = 0;
        }
        if (paidNow >= total) {
            balEl.textContent      = 'Ksh 0';
            fullWarn.style.display = 'block';
            submitBtn.disabled     = true;
        } else {
            balEl.textContent      = 'Ksh ' + nf(balance);
            fullWarn.style.display = 'none';
            submitBtn.disabled     = false;
        }
    };

    /* ── Customer phone lookup ────────────────────── */
    var lookupTimer = null;
    window.lookupCustomer = function (phone) {
        clearTimeout(lookupTimer);
        var pill = document.getElementById('customer-found-pill');
        pill.classList.remove('visible');
        if (phone.length < 9) return;

        lookupTimer = setTimeout(function () {
            fetch('{{ route("customers.lookup") }}?phone=' + encodeURIComponent(phone), {
                headers: { 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.name) {
                    document.getElementById('cart-credit-name').value = data.name;
                    document.getElementById('customer-found-name').textContent = data.name + ' (existing)';
                    pill.classList.add('visible');
                }
            })
            .catch(function () {});
        }, 400);
    };

    /* ── Confirm cart sale ────────────────────────── */
    window.confirmCartSale = function (paymentType) {
        var name    = document.getElementById('cart-credit-name').value.trim();
        var phone   = document.getElementById('cart-credit-phone').value.trim();
        var paidNow = parseFloat(document.getElementById('cart-credit-paid-now').value) || 0;

        if (paymentType === 'credit') {
            if (!phone) { document.getElementById('cart-credit-phone').focus(); return; }
            if (!name)  { document.getElementById('cart-credit-name').focus();  return; }
        }

        var cart  = getCart();
        var total = cartTotal();

        var payload = {
            items: cart.map(function (item) {
                return {
                    product_id:     item.product_id,
                    variant_id:     item.variant_id,
                    bottle_id:      item.bottle_id,
                    quantity_or_ml: item.quantity,
                    actual_price:   item.actual_price,
                    unit_price:     item.unit_price,
                };
            }),
            payment_type:    paymentType,
            customer_name:   name  || null,
            customer_phone:  phone || null,
            amount_paid_now: paidNow,
        };

        var submitBtn = paymentType === 'credit'
            ? document.getElementById('btn-confirm-cart-credit')
            : null;
        if (submitBtn) submitBtn.disabled = true;

        fetch('{{ route("sales.cart") }}', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(payload),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                onCartSaleSuccess(data, total);
            } else {
                alert(data.error || 'Something went wrong.');
                if (submitBtn) submitBtn.disabled = false;
            }
        })
        .catch(function () {
            alert('Network error — please try again.');
            if (submitBtn) submitBtn.disabled = false;
        });
    };

    function onCartSaleSuccess(data, total) {
        // Clear cart
        saveCart([]);
        closeCartSheet();

        // Update stock badges returned by server
        if (data.stock_updates) {
            data.stock_updates.forEach(function (upd) {
                updateCardStock(upd);
            });
        }

        var label = data.items_count + (data.items_count === 1 ? ' item' : ' items')
            + ' \u2014 Ksh ' + nf(data.total)
            + ' \u2014 ' + cap(data.payment_type);
        showToast('\u2713 ' + label);
    }

    function updateCardStock(upd) {
        var badge = document.getElementById('stock-' + upd.product_id);
        if (!badge) return;
        var card     = badge.closest('.product-card');
        var newStock = upd.new_stock;

        if (upd.product_type === 'measured') {
            badge.textContent = newStock > 0 ? Math.round(newStock) + 'ml left' : 'Empty';
        } else {
            badge.textContent = newStock > 0 ? Math.round(newStock) + ' in stock' : 'Out of stock';
        }
        badge.className = 'product-stock';
        if (newStock <= 0) {
            badge.className += ' stock-out';
            if (card) card.classList.add('out-of-stock');
        } else {
            var threshold = card ? parseInt(card.dataset.threshold || 3) : 3;
            if (newStock <= threshold) badge.className += ' stock-low';
        }
        if (card) card.dataset.stock = newStock;
    }

    /* ── In-sheet message bar ────────────────────── */
    function showMsg(msg, type) {
        var el = document.getElementById('sheet-msg');
        el.textContent = msg;
        el.style.display = 'block';
        el.style.background  = type === 'error' ? '#FDF0EC' : '#FEF4E8';
        el.style.color       = type === 'error' ? '#B85C38' : '#A06020';
        el.style.borderColor = type === 'error' ? '#F5DDD8' : '#F5EADB';
    }
    function hideMsg() {
        var el = document.getElementById('sheet-msg');
        if (el) { el.style.display = 'none'; el.textContent = ''; }
    }

    /* ── Toast ───────────────────────────────────── */
    function showToast(msg) {
        var toast = document.getElementById('sale-toast');
        toast.textContent = msg;
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 2800);
    }

    /* ── Helpers ─────────────────────────────────── */
    function nf(n) {
        return Number(n).toLocaleString('en-KE', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }
    function cap(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; }
    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

}());
</script>
@endsection
