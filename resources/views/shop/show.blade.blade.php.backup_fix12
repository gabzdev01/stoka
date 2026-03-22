<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->name }} — {{ $tenant->name }}</title>
<meta name="description" content="{{ $product->description ?? ($product->name . ' at ' . $tenant->name) }}">
<meta property="og:title" content="{{ $product->name }} — {{ $tenant->name }}">
<meta property="og:description" content="{{ $product->description ?? ('Shop ' . $product->name . ' at ' . $tenant->name) }}">
@if($product->photo)
<meta property="og:image" content="{{ url('/storage/' . $product->photo) }}">
@endif
<meta property="og:type" content="product">
<meta property="og:url" content="{{ url()->current() }}">
<link rel="canonical" href="{{ url()->current() }}">
@if($product->photo)
<link rel="preload" as="image" href="{{ url('/storage/' . $product->photo) }}">
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --espresso:   #1C1814;
    --terracotta: #C17F4A;
    --parchment:  #FAF7F2;
    --surface:    #F2EDE6;
    --muted:      #8C8279;
    --border:     #E8E2DA;
    --forest:     #4A6741;
    --clay:       #B85C38;
    --dark-wood:  #2C1F14;
}
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--parchment);
    color: var(--espresso);
    min-height: 100vh;
}

/* ── App banner (owner / staff logged in) ─────────────── */
.app-banner {
    background: var(--dark-wood);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    padding: 0 20px;
    height: 34px;
    font-size: 11px;
    letter-spacing: 0.03em;
}
.app-banner a {
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    transition: color 0.12s;
    white-space: nowrap;
}
.app-banner a:hover { color: rgba(255,255,255,0.85); }
.app-banner .sep { color: rgba(255,255,255,0.15); font-size: 10px; }

/* ── Nav ──────────────────────────────────────────────── */
.nav {
    background: var(--espresso);
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    position: sticky;
    top: 0;
    z-index: 20;
}
.nav-back {
    display: flex;
    align-items: center;
    gap: 7px;
    color: rgba(255,255,255,0.4);
    text-decoration: none;
    font-size: 13px;
    transition: color 0.12s;
    -webkit-tap-highlight-color: transparent;
}
.nav-back:hover { color: rgba(255,255,255,0.8); }
.nav-shop-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 18px;
    font-weight: 500;
    color: var(--parchment);
    letter-spacing: 0.05em;
}

/* ── Product layout ───────────────────────────────────── */
.product-page { display: block; }

/* ── Image column ─────────────────────────────────────── */
.img-col {
    width: 100%;
    height: 56vh;
    overflow: hidden;
    background: white;
    position: relative;
    cursor: zoom-in;
}
.img-col.zoomed {
    cursor: zoom-out;
}
.img-col.zoomed .hero-photo {
    object-fit: cover;
    cursor: grab;
}
.img-col.zoomed .hero-photo:active {
    cursor: grabbing;
}
.hero-photo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    background: white;
}
.hero-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(72px, 20vw, 120px);
    font-weight: 400;
    font-style: italic;
    color: var(--border);
    letter-spacing: -0.02em;
}

/* ── Detail column ────────────────────────────────────── */
.detail-col {
    padding: 28px 20px 140px;
    max-width: 560px;
    margin: 0 auto;
}
.detail-back { display: none; }

/* ── Category label ───────────────────────────────────── */
.prod-category {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 10px;
}

/* ── Product name ─────────────────────────────────────── */
.prod-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(26px, 7vw, 38px);
    font-weight: 500;
    line-height: 1.1;
    letter-spacing: 0.01em;
    margin-bottom: 14px;
}

/* ── Price ────────────────────────────────────────────── */
.prod-price {
    display: flex;
    align-items: baseline;
    gap: 5px;
    margin-bottom: 16px;
}
.price-num {
    font-family: 'DM Mono', monospace;
    font-size: 19px;
    font-weight: 400;
    color: var(--espresso);
    letter-spacing: -0.01em;
}
.price-unit {
    font-size: 11px;
    color: var(--muted);
    letter-spacing: 0.02em;
}

/* ── Stock status ─────────────────────────────────────── */
.avail {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 20px;
    letter-spacing: 0.01em;
}
.avail-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
.avail.in  { color: var(--forest); }
.avail.in  .avail-dot { background: var(--forest); }
.avail.low { color: var(--terracotta); }
.avail.low .avail-dot { background: var(--terracotta); }
.avail.out { color: var(--clay); }
.avail.out .avail-dot { background: var(--clay); }

/* ── Divider & description ────────────────────────────── */
.divider { height: 1px; background: var(--border); margin: 20px 0; }
.prod-desc {
    font-size: 14px;
    line-height: 1.78;
    color: #5A524C;
    white-space: pre-line;
}

/* ── Size selector ────────────────────────────────────── */
.sizes-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.13em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 12px;
}
.sizes-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 4px;
}
.size-chip {
    min-width: 44px;
    height: 44px;
    padding: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: white;
    font-size: 13px;
    font-weight: 500;
    color: var(--espresso);
    cursor: pointer;
    transition: border-color 0.12s, background 0.12s, color 0.12s;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    flex-shrink: 0;
}
.size-chip:hover { border-color: #9A8880; }
.size-chip.active {
    border: 1.5px solid var(--espresso);
    background: var(--espresso);
    color: var(--parchment);
}
.size-chip.oos {
    opacity: 0.32;
    cursor: default;
    text-decoration: line-through;
    color: var(--muted);
}
.size-chip.oos:hover { border-color: var(--border); background: white; }

/* ── Desktop CTA button (inside detail col) ───────────── */
.desktop-cta { display: none; margin-top: 32px; }
.btn-wa-desktop {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    width: 100%;
    padding: 15px 24px;
    background: var(--espresso);
    color: var(--parchment);
    border: none;
    border-radius: 12px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.01em;
    text-decoration: none;
    cursor: pointer;
    transition: opacity 0.15s;
    -webkit-tap-highlight-color: transparent;
}
.btn-wa-desktop:hover { opacity: 0.84; }
.btn-wa-desktop.unavail {
    background: var(--surface);
    color: var(--muted);
    cursor: default;
    pointer-events: none;
}
.btn-wa-desktop svg { flex-shrink: 0; }
.wa-reassurance {
    margin-top: 11px;
    text-align: center;
    font-size: 11px;
    color: var(--muted);
    letter-spacing: 0.01em;
}

/* ── Mobile sticky CTA ────────────────────────────────── */
.mobile-cta {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 12px 16px;
    padding-bottom: max(12px, env(safe-area-inset-bottom));
    background: var(--parchment);
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 30;
}
.btn-wa-mobile {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 15px;
    background: var(--espresso);
    color: var(--parchment);
    border: none;
    border-radius: 12px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.01em;
    text-decoration: none;
    -webkit-tap-highlight-color: transparent;
    transition: opacity 0.15s;
}
.btn-wa-mobile:active { opacity: 0.85; }
.btn-wa-mobile.unavail {
    background: var(--surface);
    color: var(--muted);
    pointer-events: none;
}
.btn-wa-mobile svg { flex-shrink: 0; }
.btn-wish {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: white;
    color: var(--muted);
    cursor: pointer;
    transition: border-color 0.12s, color 0.12s, background 0.12s;
    flex-shrink: 0;
    -webkit-tap-highlight-color: transparent;
}
.btn-wish:hover { border-color: #C88; color: #C44; }
.btn-wish.saved { background: #FFF5F5; border-color: #E8AAAA; color: #C85050; }

/* ── Footer ───────────────────────────────────────────── */
.shop-footer {
    text-align: center;
    padding: 24px 20px 32px;
    font-size: 10px;
    color: var(--border);
    letter-spacing: 0.07em;
    text-transform: uppercase;
    display: none;
}
.shop-footer a { color: var(--muted); text-decoration: none; }

/* ── Desktop two-column layout ────────────────────────── */
@media (min-width: 768px) {
    .product-page {
        display: flex;
        align-items: flex-start;
        min-height: calc(100vh - 52px);
    }
    .img-col {
        width: 50%;
        height: calc(100vh - 52px);
        position: sticky;
        top: 52px;
        align-self: flex-start;
        flex-shrink: 0;
    }
    .detail-col {
        flex: 1;
        padding: 52px 60px 80px;
        max-width: none;
        margin: 0;
    }
    .detail-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: rgba(255,255,255,0.0);
        color: var(--muted);
        text-decoration: none;
        font-size: 12px;
        letter-spacing: 0.02em;
        margin-bottom: 36px;
        transition: color 0.12s;
    }
    .detail-back:hover { color: var(--espresso); }
    .prod-name { font-size: clamp(30px, 3.5vw, 46px); }
    .desktop-cta { display: block; }
    .mobile-cta { display: none !important; }
    .shop-footer { display: block; }
}

@media (min-width: 1100px) {
    .detail-col { padding: 60px 72px 80px; }
}

/* ── Demo acquisition bar ────────────────────────────── */
.demo-bar {
    background: #2C1F14;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    padding: 9px 20px;
    font-size: 11px;
    text-align: center;
}
.demo-bar-text {
    color: rgba(255,255,255,0.45);
    letter-spacing: 0.02em;
    line-height: 1.7;
}
.demo-preview-name {
    font-family: 'Cormorant Garamond', serif;
    font-style: italic;
    font-size: 13px;
    color: var(--terracotta);
    letter-spacing: 0.03em;
}
.demo-bar-link {
    color: #C17F4A;
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 0.03em;
    white-space: nowrap;
    transition: opacity 0.12s;
}
.demo-bar-link:hover { opacity: 0.8; }
.demo-bar-back {
    color: rgba(255,255,255,0.28);
    text-decoration: none;
    white-space: nowrap;
    font-size: 10px;
    flex-shrink: 0;
    transition: color 0.12s;
}
.demo-bar-back:hover { color: rgba(255,255,255,0.65); }
</style>
</head>
<body>

@php
    $waPhone = preg_replace('/[^0-9]/', '', $tenant->owner_whatsapp ?? $tenant->owner_phone ?? '');
    $oos = false;
    $stockText = 'In stock';
    $stockClass = 'in';
    $lowThreshold = $product->low_stock_threshold ?? 3;

    if ($product->track_stock) {
        if ($product->type === 'unit') {
            if ($product->stock <= 0) {
                $oos = true; $stockText = 'Out of stock'; $stockClass = 'out';
            } elseif ($product->stock <= $lowThreshold) {
                $stockText = 'Low stock — ' . $product->stock . ' remaining'; $stockClass = 'low';
            }
        } elseif ($product->type === 'variant') {
            $totalStock = $product->variants->sum('stock');
            if ($totalStock <= 0) {
                $oos = true; $stockText = 'Out of stock'; $stockClass = 'out';
            } elseif ($totalStock <= $lowThreshold) {
                $stockText = 'Low stock — ' . $totalStock . ' remaining'; $stockClass = 'low';
            }
        } elseif ($product->type === 'measured') {
            $remainingMl = $product->bottles->first()?->remaining_ml ?? 0;
            if ($remainingMl <= 0) {
                $oos = true; $stockText = 'Out of stock'; $stockClass = 'out';
            }
        }
    }
@endphp


@if($isDemo)
@php
    $previewName = request('preview') ? htmlspecialchars(request('preview'), ENT_QUOTES) : null;
    $refBack     = request('back') ? 'https://' . htmlspecialchars(request('back'), ENT_QUOTES) . '/dashboard' : null;
    $waPhone     = '254741641925';
    $waMsg       = $previewName
        ? urlencode('Hi! I just browsed a shop preview on Stoka for ' . $previewName . ' and I am interested in getting my boutique online. Can we talk?')
        : urlencode('Hi! I would like to get my boutique on Stoka. Can you tell me more?');
@endphp
<div class="demo-bar">
    <span class="demo-bar-text">
        @if($previewName)
            Exploring <span class="demo-preview-name">{{ $previewName }}</span> on Stoka &nbsp;&middot;&nbsp; <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" class="demo-bar-link">Get your own →</a>
        @else
            You're exploring a demo shop &nbsp;&middot;&nbsp; <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" class="demo-bar-link">Get your own →</a>
        @endif
    </span>
    @if($refBack)
    <a href="{{ $refBack }}" class="demo-bar-back">← Back</a>
    @endif
</div>
@endif

{{-- App banner: visible when owner or staff is logged in --}}
@if(session('auth_role'))
<div class="app-banner">
    @if(session('auth_role') === 'owner')
        <a href="{{ route('dashboard') }}">← Dashboard</a>
        <span class="sep">·</span>
        <a href="{{ route('products.edit', $product->id) }}">Edit this product</a>
    @else
        <a href="{{ url('/') }}">← Back to POS</a>
    @endif
</div>
@endif

{{-- Nav --}}
<div class="nav">
    <a href="{{ route('shop.index') }}" class="nav-back">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Shop
    </a>
    <div class="nav-shop-name">{{ $tenant->name }}</div>
    <div style="width:42px"></div>
</div>

{{-- Product page --}}
<div class="product-page">

    {{-- Left: image --}}
    <div class="img-col">
        @if($product->photo)
            <img src="{{ url('/storage/' . $product->photo) }}"
                 alt="{{ $product->name }}"
                 class="hero-photo"
                 loading="eager"
                 width="800" height="1000">
        @else
            <div class="hero-placeholder">{{ mb_strtoupper(mb_substr($product->name, 0, 1)) }}</div>
        @endif
    </div>

    {{-- Right: details --}}
    <div class="detail-col">

        {{-- Breadcrumbs --}}
        <div class="breadcrumbs">
            <a href="{{ route('shop.index') }}">Shop</a>
            <span class="breadcrumb-sep">›</span>
            @if($product->category)
            <a href="{{ route('shop.index', ['cat' => $product->category]) }}">{{ $product->category }}</a>
            <span class="breadcrumb-sep">›</span>
            @endif
            <span class="breadcrumb-current">{{ $product->name }}</span>
        </div>

        @if($product->category)
        <div class="prod-category">{{ $product->category }}</div>
        @endif

        <h1 class="prod-name">{{ $product->name }}</h1>

        <div class="prod-price">
            <span class="price-num">{{ tenant('currency_symbol') }} {{ number_format((int)$product->shelf_price) }}</span>
            @if($product->type === 'measured')
            <span class="price-unit">per ml</span>
            @endif
        </div>

        @if($product->track_stock)
        <div class="avail {{ $stockClass }}">
            <span class="avail-dot"></span>
            {{ $stockText }}
        </div>
        @endif

        @if($product->description)
        <div class="divider"></div>
        <p class="prod-desc">{{ $product->description }}</p>
        @endif

        {{-- Sizes --}}
        @if($product->type === 'variant' && $product->variants->isNotEmpty())
        <div class="divider"></div>
        <div class="sizes-label">Select size</div>
        <div class="sizes-row" id="sizesRow">
            @foreach($product->variants as $v)
            @php $vOos = $product->track_stock && $v->stock <= 0; @endphp
            <div class="size-chip {{ $vOos ? 'oos' : '' }}"
                 data-size="{{ $v->size }}"
                 @if(!$vOos) onclick="selectSize(this)" @endif>{{ $v->size }}</div>
            @endforeach
        </div>
        @endif

        {{-- Desktop CTA --}}
        <div class="desktop-cta">
            @if(!$oos && $waPhone)
            <a href="#" class="btn-wa-desktop" id="waBtnDesktop" target="_blank" rel="noopener">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.956-1.418A9.955 9.955 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                Ask about this
            </a>
            @else
            <div class="btn-wa-desktop unavail">{{ $oos ? 'Currently unavailable' : 'Chat unavailable' }}</div>
            @endif
            <p class="wa-reassurance">Responds on WhatsApp &middot; Usually within an hour</p>
        </div>

    </div>
</div>

{{-- Footer (desktop only) --}}
<div class="shop-footer">
    A <a href="https://getstoka.com" target="_blank" rel="noopener">Stoka</a> shop
</div>

{{-- Mobile sticky CTA --}}
<div class="mobile-cta">
    @if(!$oos && $waPhone)
    <a href="#" class="btn-wa-mobile" id="waBtnMobile" target="_blank" rel="noopener">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.956-1.418A9.955 9.955 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
        Ask about this
    </a>
    @else
    <div class="btn-wa-mobile unavail">{{ $oos ? 'Currently unavailable' : 'Chat unavailable' }}</div>
    @endif
    <button class="btn-wish" id="wishBtn" aria-label="Save" onclick="toggleWish()">
        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
    </button>
</div>

<script>
var selectedSize = null;
var waPhone = {{ json_encode($waPhone) }};
var prodName = {{ json_encode($product->name) }};
var prodPrice = '{{ tenant("currency_symbol") }} {{ number_format((int)$product->shelf_price) }}';
var prodUrl = window.location.href;
var wishKey = 'wish_{{ $product->id }}';

function buildWaMsg() {
    var msg = 'Hi! I just saw the ' + prodName;
    if (selectedSize) msg += ' in size ' + selectedSize;
    msg += ' (' + prodPrice + ') on your shop and I\'m interested. Is it available?';
    msg += '\n' + prodUrl;
    return msg;
}
function updateWaBtns() {
    if (!waPhone) return;
    var url = 'https://wa.me/' + waPhone + '?text=' + encodeURIComponent(buildWaMsg());
    var m = document.getElementById('waBtnMobile');
    var d = document.getElementById('waBtnDesktop');
    if (m) m.href = url;
    if (d) d.href = url;
}
function selectSize(el) {
    document.querySelectorAll('.size-chip:not(.oos)').forEach(function(c) { c.classList.remove('active'); });
    el.classList.add('active');
    selectedSize = el.dataset.size;
    updateWaBtns();
}
function toggleWish() {
    var btn = document.getElementById('wishBtn');
    var saved = btn.classList.toggle('saved');
    if (saved) {
        localStorage.setItem(wishKey, '1');
        btn.querySelector('svg').setAttribute('fill', 'currentColor');
    } else {
        localStorage.removeItem(wishKey);
        btn.querySelector('svg').setAttribute('fill', 'none');
    }
}
updateWaBtns();
(function initWish() {
    if (localStorage.getItem(wishKey)) {
        var btn = document.getElementById('wishBtn');
        if (btn) { btn.classList.add('saved'); btn.querySelector('svg').setAttribute('fill', 'currentColor'); }
    }
})();

// Preview name swap (demo ?preview=Name)
(function() {
    var params = new URLSearchParams(window.location.search);
    var preview = params.get('preview');
    if (preview) {
        document.querySelectorAll('.nav-shop-name').forEach(function(el) {
            el.textContent = preview;
        });
        document.title = document.title.replace(/^.*?—/, preview + ' —');
    }
})();

// Image zoom on click (mobile & desktop)
(function() {
    var imgCol = document.querySelector('.img-col');
    var heroPhoto = document.querySelector('.hero-photo');
    if (!imgCol || !heroPhoto) return;
    
    imgCol.addEventListener('click', function() {
        imgCol.classList.toggle('zoomed');
    });
})();
</script>
</body>
</html>
