<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#1C1814">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="alternate icon" href="/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $tenant->name }}</title>
<meta name="description" content="Browse {{ $tenant->name }} — shop online and enquire via WhatsApp.">
<meta property="og:title" content="{{ $tenant->name }}">
<meta property="og:description" content="Browse {{ $tenant->name }} — shop online and enquire via WhatsApp.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<link rel="canonical" href="{{ route('shop.index') }}">
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
}
html { scroll-behavior: smooth; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--parchment);
    color: var(--espresso);
    min-height: 100vh;
}
        100% { opacity: 0; visibility: hidden; }
    }

/* ── Owner Navigation ────────────────────────────────────── */
.owner-nav {
    background: var(--dark-wood);
    padding: 0 20px;
    height: 48px;
    display: flex;
    align-items: center;
}
.back-to-dash {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: rgba(250, 247, 242, 0.6);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.01em;
    transition: color 0.2s;
    padding: 8px 0;
}
.back-to-dash:hover {
    color: var(--parchment);
}
.back-to-dash svg {
    flex-shrink: 0;
}

/* ── Search & Sort Bar ───────────────────────────────────── */
.search-bar-wrap {
    background: var(--parchment);
    border-bottom: 1px solid var(--border);
    padding: 14px 20px 12px;
    display: flex;
    gap: 10px;
    align-items: center;
}
.search-form {
    flex: 1;
    min-width: 0;
}
.search-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.search-icon {
    position: absolute;
    left: 12px;
    color: var(--muted);
    pointer-events: none;
}
.search-input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    color: var(--espresso);
    background: white;
    transition: border-color 0.15s;
}
.search-input:focus {
    outline: none;
    border-color: var(--espresso);
}
.search-input::placeholder {
    color: var(--muted);
    opacity: 0.5;
}
.search-clear {
    position: absolute;
    right: 12px;
    color: var(--muted);
    text-decoration: none;
    font-size: 16px;
    padding: 4px;
    transition: color 0.12s;
}
.search-clear:hover { color: var(--espresso); }
.sort-select {
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    color: var(--espresso);
    background: white;
    cursor: pointer;
    min-width: 140px;
    flex-shrink: 0;
}
.sort-select:focus {
    outline: none;
    border-color: var(--espresso);
}
@media (max-width: 560px) {
    .search-bar-wrap { flex-direction: column; gap: 8px; }
    .sort-select { width: 100%; min-width: 0; }
}

/* ── Category chips ──────────────────────────────────────── */
.cat-bar-outer {
    background: var(--parchment);
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: 0;
    z-index: 10;
}
.cat-bar {
    display: flex;
    gap: 8px;
    padding: 11px 20px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    max-width: 1020px;
    margin: 0 auto;
}
.cat-bar::-webkit-scrollbar { display: none; }
.cat-chip {
    flex-shrink: 0;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--muted);
    transition: all 0.12s;
    white-space: nowrap;
}
.cat-chip:hover { border-color: var(--espresso); color: var(--espresso); }
.cat-chip.active { background: var(--espresso); border-color: var(--espresso); color: var(--parchment); }

/* ── Grid ────────────────────────────────────────────────── */
.grid-wrap {
    padding: 14px 20px 60px;
    max-width: 1020px;
    margin: 0 auto;
}
.product-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
@media (min-width: 560px) {
    .product-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
}
@media (min-width: 860px) {
    .product-grid { grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .grid-wrap { padding: 36px 32px 80px; }
}
@media (min-width: 1100px) {
    .grid-wrap { padding: 40px 40px 80px; }
    .cat-bar { padding: 14px 40px; }
}

/* ── Product card (ALIVE with terracotta) ────────────────── */
.prod-card {
    display: block;
    text-decoration: none;
    color: inherit;
    position: relative;
    transition: transform 0.25s;
}
.prod-card:hover {
    transform: translateY(-2px);
}
.prod-card:hover .prod-photo {
    opacity: 0.92;
}
.prod-card:hover .prod-name {
    color: var(--terracotta);
}

.prod-photo-wrap {
    width: 100%;
    aspect-ratio: 3 / 4;
    overflow: hidden;
    background: #ffffff;
    position: relative;
    border: 2px solid transparent;
    border-radius: 4px;
    transition: border-color 0.3s;
}
.prod-card:hover .prod-photo-wrap {
    border-color: rgba(193, 127, 74, 0.2);
}
.prod-photo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    transition: opacity 0.2s ease;
}
.prod-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(40px, 10vw, 64px);
    font-weight: 400;
    color: var(--border);
    letter-spacing: 0.02em;
    font-style: italic;
}

/* ── Badges ──────────────────────────────────────────────── */
.badge-new {
    position: absolute;
    top: 10px;
    left: 10px;
    background: var(--terracotta);
    color: white;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 50px;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(193, 127, 74, 0.3);
    animation: badge-pulse 2s ease-in-out infinite;
}
@keyframes badge-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
.badge-oos {
    position: absolute;
    inset: 0;
    background: rgba(250,247,242,0.55);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 14px;
    z-index: 2;
}
.badge-oos span {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--muted);
    background: var(--parchment);
    padding: 4px 10px;
    border-radius: 50px;
}

/* ── Product info ─────────────────────────────────────────── */
.prod-info {
    padding: 12px 4px 20px;
}
.prod-card {
    opacity: 1;
    transition: opacity 0.25s, transform 0.25s;
}
.prod-cat-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 6px;
}
.prod-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(15px, 3.5vw, 17px);
    font-weight: 400;
    line-height: 1.25;
    color: var(--espresso);
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.prod-price {
    font-family: 'DM Mono', monospace;
    font-size: 13px;
    font-weight: 400;
    color: var(--muted);
}
.prod-price-unit {
    font-size: 11px;
    color: var(--muted);
    margin-left: 2px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.prod-sizes {
    font-size: 11px;
    color: var(--muted);
    margin-top: 4px;
    letter-spacing: 0.05em;
    font-weight: 500;
}

/* ── Empty ────────────────────────────────────────────────── */
.empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px;
    font-style: italic;
    color: var(--border);
}

/* ── Footer ───────────────────────────────────────────────── */
.shop-footer {
    text-align: center;
    padding: 28px 20px 40px;
    border-top: 1px solid var(--border);
    font-size: 11px;
    color: var(--muted);
    letter-spacing: 0.04em;
}
.shop-footer a {
    color: var(--espresso);
    text-decoration: none;
    font-weight: 600;
    border-bottom: 1px solid var(--border);
    transition: border-color 0.15s;
}
.shop-footer a:hover { border-bottom-color: var(--espresso); }

/* ── Demo acquisition bar ───────────────────────────────── */
.demo-bar {
    background: var(--espresso);
    padding: 10px 20px;
    font-size: 12px;
    text-align: center;
}
.demo-bar-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}
.demo-bar-text {
    color: rgba(250,247,242,0.78);
    letter-spacing: 0.02em;
    line-height: 1.6;
}
.demo-preview-name {
    font-family: 'Cormorant Garamond', serif;
    font-style: italic;
    font-size: 14px;
    color: var(--terracotta);
    letter-spacing: 0.03em;
}
.demo-bar-link {
    color: var(--parchment);
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 0.02em;
    white-space: nowrap;
    padding-bottom: 1px;
    border-bottom: 1px solid rgba(250,247,242,0.3);
    transition: opacity 0.12s;
}
.demo-bar-link:hover { opacity: 0.72; }
.demo-bar-back {
    color: rgba(250,247,242,0.35);
    text-decoration: none;
    white-space: nowrap;
    font-size: 10px;
    flex-shrink: 0;
    transition: color 0.12s;
}
.demo-bar-back:hover { color: rgba(250,247,242,0.7); }


/* ── Floating WhatsApp ───────────────────────────────── */
.wa-float {
    position: fixed;
    bottom: 24px;
    right: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #25D366;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 18px rgba(0,0,0,0.22);
    text-decoration: none;
    z-index: 200;
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.3s ease, transform 0.3s ease, background 0.15s;
    pointer-events: none;
}
.wa-float.visible {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}
.wa-float:hover { background: #1ebe5d; }
</style>
</head>
<body>

@if($isDemo)
@php
    $refBack = request('back') ? 'https://' . htmlspecialchars(request('back'), ENT_QUOTES) . '/dashboard' : null;
    $waPhone = '254741641925';
@endphp

{{-- Demo bar: JS-driven, shown once state is known --}}
<div id="demo-bar" class="demo-bar" style="display:none">
    <div class="demo-bar-inner">
        <span id="demo-bar-text" class="demo-bar-text"></span>
        <a href="https://wa.me/{{ $waPhone }}" id="demo-bar-cta" target="_blank" class="demo-bar-link">Get your own →</a>
        @if($refBack)
        <a href="{{ $refBack }}" class="demo-bar-back">← Back</a>
        @endif
    </div>
</div>
@endif




{{-- ── Header (Owner only - Back to Dashboard) ──────────────────────── --}}
@if(session('auth_role') === 'owner')
<div class="owner-nav">
    <a href="{{ route('dashboard') }}" class="back-to-dash">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        <span>Dashboard</span>
    </a>
</div>
@endif

{{-- ── Search & Filter Bar ──────────────────────────────────────────── --}}
<div class="search-bar-wrap">
    <form action="{{ route('shop.index') }}" method="GET" class="search-form">
        <div class="search-input-wrap">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" 
                   placeholder="Search products..." 
                   class="search-input"
                   autocomplete="off">
            @if(request('q'))
            <a href="{{ route('shop.index') }}" class="search-clear">✕</a>
            @endif
        </div>
        @if(request('cat'))
        <input type="hidden" name="cat" value="{{ request('cat') }}">
        @endif
    </form>
    
    @if($products->count() > 3)
    <select class="sort-select" onchange="window.location.href=updateSort(this.value)">
        <option value="">Sort by</option>
        <option value="category" {{ request('sort') === 'category' ? 'selected' : '' }}>Category</option>
        <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
        <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Alphabetical</option>
    </select>
    @endif
</div>

{{-- ── Category chips ──────────────────────────────────────────────── --}}
@if($categories->count() > 1)
<div class="cat-bar-outer">
<div class="cat-bar">
    <a href="{{ route('shop.index') }}" class="cat-chip {{ !$filterCat ? 'active' : '' }}">All</a>
    @foreach($categories as $cat)
    <a href="{{ route('shop.index', ['cat' => $cat]) }}"
       class="cat-chip {{ $filterCat === $cat ? 'active' : '' }}">{{ $cat }}</a>
    @endforeach
</div>
</div>
@endif

{{-- ── Grid ────────────────────────────────────────────────────────── --}}
<div class="grid-wrap">
@php
    $visible = $filterCat ? $products->where('category', $filterCat)->values() : $products;
    $cutoff  = now()->subDays(14);
@endphp
<div class="product-grid">
    @forelse($visible as $p)
    @php
        $isNew = $p->created_at?->greaterThan($cutoff);
        $oos   = false;
        if ($p->track_stock) {
            if ($p->type === 'unit')     $oos = $p->stock <= 0;
            elseif ($p->type === 'variant')  $oos = $p->variants->sum('stock') <= 0;
            elseif ($p->type === 'measured') $oos = ($p->bottles->first()?->remaining_ml ?? 0) <= 0;
        }
        $availSizes = $p->type === 'variant'
            ? $p->variants->where('stock', '>', 0)->pluck('size')->filter()->values()
            : collect();
    @endphp
    <a href="{{ route('shop.show', $p) }}" class="prod-card">
        <div class="prod-photo-wrap">
            @if($p->photo)
                <img src="{{ url('/storage/' . $p->photo) }}"
                     alt="{{ $p->name }}"
                     class="prod-photo"
                     loading="lazy">
            @else
                <div class="prod-placeholder">{{ mb_strtoupper(mb_substr($p->name, 0, 1)) }}</div>
            @endif
            @if($isNew && !$oos && !$isDemo)<div class="badge-new">New</div>@endif
            @if($oos)
            <div class="badge-oos"><span>Unavailable</span></div>
            @endif
        </div>
        <div class="prod-info">
            @if($p->category)
            <div class="prod-cat-label">{{ $p->category }}</div>
            @endif
            <div class="prod-name">{{ $p->name }}</div>
            <div class="prod-price">{{ tenant('currency_symbol') }} {{ number_format((int)$p->shelf_price) }}@if($p->type === 'measured')<span class="prod-price-unit">/ml</span>@endif</div>
            @if($availSizes->isNotEmpty())
            <div class="prod-sizes">{!! $availSizes->implode(' · ') !!}</div>
            @endif
        </div>
    </a>
    @empty
    <div class="empty">Nothing in this category yet.</div>
    @endforelse
</div>
</div>

{{-- ── Footer ───────────────────────────────────────────────────────── --}}
<div class="shop-footer">
    Powered by <a href="https://tempforest.com/register" target="_blank" rel="noopener">Stoka</a> &middot; Boutique management, aware by design.
</div>



@if(!$isDemo && ($tenant->owner_whatsapp || $tenant->owner_phone))
@php $waFloatPhone = preg_replace('/[^0-9]/', '', $tenant->owner_whatsapp ?? $tenant->owner_phone); @endphp
<a href="https://wa.me/{{ $waFloatPhone }}?text={{ urlencode('Hi, I would like to enquire about something from your shop.') }}"
   id="wa-float" class="wa-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
        <path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.956-1.418A9.955 9.955 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z" fill="none" stroke="currentColor" stroke-width="1.5"/>
    </svg>
</a>
@endif
<script>
(function () {
    var STORAGE_NAME = 'demo_preview_name';
    var STORAGE_SKIP = 'demo_skipped';
    var waPhone      = '254741641925';

    var params     = new URLSearchParams(window.location.search);
    var urlName    = params.get('preview');
    var storedName = localStorage.getItem(STORAGE_NAME);
    var skipped    = localStorage.getItem(STORAGE_SKIP);
    var activeName = urlName || storedName || null;

    var panel   = document.getElementById('boutique-panel');
    var demoBar = document.getElementById('demo-bar');
    var barText = document.getElementById('demo-bar-text');
    var barCta  = document.getElementById('demo-bar-cta');

    function buildMsg(name) {
        return name
            ? encodeURIComponent('Hi! I was shown a preview of my boutique on Stoka — it looks great. I am ' + name + ' and I would like to get started.')
            : encodeURIComponent('Hi! I would like to get my boutique on Stoka. Can you tell me more?');
    }

    function applyName(name) {
        document.querySelectorAll('.shop-name').forEach(function (el) { el.textContent = name; });
        document.title = name;
        if (barText) barText.innerHTML = 'Viewing <span class="demo-preview-name">' + name + '</span> on Stoka';
        if (barCta)  barCta.href = 'https://wa.me/' + waPhone + '?text=' + buildMsg(name);
        showBar();
    }

    function showBar() {
        if (demoBar) demoBar.style.display = '';
        if (panel)   panel.style.display = 'none';
    }

    function showPanel() {
        if (panel)   panel.style.display = '';
        if (demoBar) demoBar.style.display = 'none';
    }

    function skipDemo() {
        localStorage.setItem(STORAGE_SKIP, '1');
        if (panel) {
            panel.style.transition = 'opacity 0.25s ease';
            panel.style.opacity = '0';
            setTimeout(function () {
                panel.style.display = 'none';
                panel.style.opacity = '';
                panel.style.transition = '';
                if (barText) barText.textContent = 'Browsing a demo shop';
                if (barCta)  barCta.href = 'https://wa.me/' + waPhone + '?text=' + buildMsg(null);
                showBar();
            }, 260);
        } else {
            if (barText) barText.textContent = 'Browsing a demo shop';
            if (barCta)  barCta.href = 'https://wa.me/' + waPhone + '?text=' + buildMsg(null);
            showBar();
        }
    }

    // ── Init ──────────────────────────────────────────────────────
    if (activeName) {
        if (urlName && urlName !== storedName) localStorage.setItem(STORAGE_NAME, urlName);
        if (!urlName && storedName) {
            var u = new URL(window.location.href);
            u.searchParams.set('preview', storedName);
            history.replaceState(null, '', u.toString());
        }
        applyName(activeName);
    } else if (skipped) {
        if (barText) barText.textContent = 'Browsing a demo shop';
        if (barCta)  barCta.href = 'https://wa.me/' + waPhone + '?text=' + buildMsg(null);
        showBar();
    } else {
        showPanel();
    }

    // ── Form submit ───────────────────────────────────────────────
    var form = document.getElementById('boutique-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var input = document.getElementById('boutique-input');
            var name  = input ? input.value.trim() : '';
            if (!name) { skipDemo(); return; }
            localStorage.setItem(STORAGE_NAME, name);
            var u = new URL(window.location.href);
            u.searchParams.set('preview', name);
            window.location.href = u.toString();
        });
    }

    // ── Skip button ───────────────────────────────────────────────
    var skipBtn = document.getElementById('boutique-skip');
    if (skipBtn) skipBtn.addEventListener('click', skipDemo);
})();
</script>
<script>
function updateSort(sort) {
    var url = new URL(window.location.href);
    if (sort) url.searchParams.set('sort', sort);
    else url.searchParams.delete('sort');
    return url.toString();
}
</script>
<script>
(function() {
    var btn = document.getElementById('wa-float');
    if (!btn) return;
    window.addEventListener('scroll', function() {
        btn.classList.toggle('visible', window.scrollY > 300);
    }, { passive: true });
})();
</script>

{{-- ── PWA install prompt bar ──────────────────────────────────────── --}}
@php $shopDisplayName = session('demo_shop_name', $tenant->name ?? 'this boutique'); @endphp
<div id="pwa-bar" style="display:none;">
  <span id="pwa-bar-text">Add <strong id="pwa-bar-name">{{ $shopDisplayName }}</strong> to your home screen</span>
  <button id="pwa-install-btn" aria-label="Install">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="3" x2="12" y2="15"/><polyline points="7 10 12 15 17 10"/><line x1="3" y1="21" x2="21" y2="21"/></svg>
  </button>
</div>
<!-- iOS instruction (shown instead of install bar on Safari) -->
<div id="ios-hint" style="display:none;">
  <span>Tap
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="vertical-align:middle;margin:0 2px;"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
    then "Add to Home Screen"
  </span>
  <button id="ios-hint-close">✕</button>
</div>

<style>
#pwa-bar {
  position: fixed; bottom: 0; left: 0; width: 100%; z-index: 900;
  background: #1C1814; color: #FAF7F2;
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; gap: 12px;
  font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px;
  transform: translateY(100%); transition: transform 0.3s ease;
  will-change: transform;
}
#pwa-bar.pwa-visible { transform: translateY(0); }
#pwa-bar strong { color: #C17F4A; }
#pwa-install-btn {
  background: #C17F4A; border: none; border-radius: 8px;
  padding: 8px 14px; color: #FAF7F2; cursor: pointer;
  font-size: 12px; font-weight: 600; white-space: nowrap;
  display: flex; align-items: center; gap: 6px;
  transition: opacity 0.2s; flex-shrink: 0;
}
#pwa-install-btn:hover { opacity: 0.85; }
#ios-hint {
  position: fixed; bottom: 0; left: 0; width: 100%; z-index: 900;
  background: #1C1814; color: #FAF7F2;
  display: flex; align-items: center; justify-content: center; gap: 10px;
  padding: 14px 20px; font-size: 13px;
  transform: translateY(100%); transition: transform 0.3s ease;
}
#ios-hint.pwa-visible { transform: translateY(0); }
#ios-hint svg { flex-shrink: 0; }
#ios-hint-close {
  position: absolute; right: 16px; background: none; border: none;
  color: rgba(250,247,242,0.5); font-size: 14px; cursor: pointer; padding: 4px;
}
</style>

<script>
(function(){
  // Register service worker
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  }

  var deferredPrompt = null;
  var triggered = false;
  var bar = document.getElementById('pwa-bar');
  var iosHint = document.getElementById('ios-hint');

  // iOS detection
  var isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
  var isStandalone = window.navigator.standalone === true;
  var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

  // Android: capture install prompt
  window.addEventListener('beforeinstallprompt', function(e) {
    e.preventDefault();
    deferredPrompt = e;
  });

  function showBar() {
    if (triggered) return;
    if (isIOS && isSafari && !isStandalone) {
      // iOS — show share instruction
      iosHint.style.display = 'flex';
      setTimeout(function(){ iosHint.classList.add('pwa-visible'); }, 50);
      triggered = true;
    } else if (deferredPrompt) {
      // Android/Chrome — show install button
      bar.style.display = 'flex';
      setTimeout(function(){ bar.classList.add('pwa-visible'); }, 50);
      triggered = true;
    }
  }

  // Trigger: scroll past ~6 products (approx 800px) then wait 20s
  var scrollThreshold = 800;
  var scrollMet = false;
  window.addEventListener('scroll', function() {
    if (!scrollMet && window.scrollY > scrollThreshold) {
      scrollMet = true;
      setTimeout(showBar, 20000);
    }
  }, { passive: true });

  // Trigger: on product page (URL contains /shop/) fire immediately after 20s
  if (window.location.pathname.indexOf('/shop/') > -1 && window.location.pathname.length > 6) {
    setTimeout(showBar, 20000);
  }

  // Install click
  var installBtn = document.getElementById('pwa-install-btn');
  if (installBtn) {
    installBtn.addEventListener('click', function() {
      if (!deferredPrompt) return;
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then(function(){ deferredPrompt = null; });
      bar.classList.remove('pwa-visible');
    });
  }

  // iOS close
  var iosClose = document.getElementById('ios-hint-close');
  if (iosClose) {
    iosClose.addEventListener('click', function(){
      iosHint.classList.remove('pwa-visible');
    });
  }

  // Hide on scroll up, show on scroll down
  var lastY = 0;
  window.addEventListener('scroll', function() {
    var y = window.scrollY;
    if (!triggered) { lastY = y; return; }
    var el = (isIOS && isSafari && !isStandalone) ? iosHint : bar;
    if (y < lastY - 10) el.classList.remove('pwa-visible');
    else if (y > lastY + 10) el.classList.add('pwa-visible');
    lastY = y;
  }, { passive: true });
})();
</script>

</body>
</html>
