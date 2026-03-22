<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->name }} — {{ $tenant->name }}</title>
<meta name="description" content="{{ $product->description ?? ($product->name . ' at ' . $tenant->name) }}">
@if($product->photo)
<meta property="og:image" content="{{ url('/storage/' . $product->photo) }}">
@endif
<meta property="og:type" content="product">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
    --espresso:#1C1814;--terracotta:#C17F4A;--parchment:#FAF7F2;
    --surface:#F2EDE6;--muted:#8C8279;--border:#E8E2DA;
    --forest:#4A6741;--clay:#B85C38;--dark-wood:#2C1F14;
}
html{scroll-behavior:smooth;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--parchment);color:var(--espresso);min-height:100vh;-webkit-font-smoothing:antialiased;}
a{color:inherit;text-decoration:none;}

/* ── Demo bar ─────────────────────────────────────────── */
.demo-bar{background:var(--dark-wood);padding:9px 20px;font-size:11px;text-align:center;display:flex;align-items:center;justify-content:center;gap:14px;flex-wrap:wrap;}
.demo-bar-text{color:rgba(250,247,242,0.45);letter-spacing:0.02em;}
.demo-bar-link{color:var(--terracotta);font-weight:600;transition:opacity 0.15s;}
.demo-bar-link:hover{opacity:0.75;}
.demo-preview-name{font-family:'Cormorant Garamond',serif;font-style:italic;color:var(--terracotta);}
.demo-bar-back{color:rgba(250,247,242,0.25);font-size:10px;transition:color 0.15s;}
.demo-bar-back:hover{color:rgba(250,247,242,0.6);}

/* ── App banner (owner/staff) ─────────────────────────── */
.app-banner{background:var(--espresso);display:flex;align-items:center;justify-content:center;gap:20px;padding:0 20px;height:40px;font-size:11px;border-bottom:1px solid rgba(250,247,242,0.06);}
.app-banner a{color:rgba(250,247,242,0.35);transition:color 0.15s;letter-spacing:0.02em;}
.app-banner a:hover{color:rgba(250,247,242,0.75);}
.app-banner .sep{color:rgba(250,247,242,0.1);}

/* ── Breadcrumb ───────────────────────────────────────── */
.breadcrumb{padding:13px 24px;font-size:11px;color:var(--muted);display:flex;align-items:center;gap:7px;border-bottom:1px solid var(--border);background:var(--parchment);}
.breadcrumb a{color:var(--muted);transition:color 0.12s;}
.breadcrumb a:hover{color:var(--espresso);}
.bc-sep{color:var(--border);}
.bc-cur{color:var(--espresso);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}

/* ── Product layout ───────────────────────────────────── */
.product-page{display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 40px);}
@media(max-width:768px){.product-page{grid-template-columns:1fr;}}

/* ── Image column ─────────────────────────────────────── */
.img-col{background:var(--surface);display:flex;align-items:center;justify-content:center;min-height:55vw;}
@media(min-width:769px){.img-col{position:sticky;top:0;height:100vh;}}
.hero-photo{width:100%;height:100%;object-fit:contain;display:block;padding:40px;}
@media(max-width:768px){
    .img-col{min-height:72vw;}
    .hero-photo{padding:24px;}
}
.hero-placeholder{font-family:'Cormorant Garamond',serif;font-size:80px;font-weight:400;color:var(--border);font-style:italic;}

/* ── Detail column ────────────────────────────────────── */
.detail-col{padding:48px 44px 60px;display:flex;flex-direction:column;}
@media(max-width:768px){.detail-col{padding:24px 20px 48px;}}
.prod-category{font-size:10px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:var(--muted);margin-bottom:14px;}
.prod-name{font-family:'Cormorant Garamond',serif;font-size:clamp(28px,4vw,44px);font-weight:400;line-height:1.1;color:var(--espresso);margin-bottom:16px;}
.prod-price{font-family:'DM Mono',monospace;font-size:22px;font-weight:400;color:var(--espresso);margin-bottom:14px;}
.price-unit{font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;color:var(--muted);margin-left:4px;}
.avail{display:flex;align-items:center;gap:7px;font-size:12px;font-weight:500;margin-bottom:28px;}
.avail.in{color:var(--forest);}
.avail.low{color:var(--terracotta);}
.avail.out{color:var(--muted);}
.avail-dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;}
.divider{height:1px;background:var(--border);margin:0 0 24px;}
.prod-desc{font-size:14px;line-height:1.8;color:var(--muted);margin-bottom:32px;}

/* ── Sizes ────────────────────────────────────────────── */
.sizes-label{font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;}
.sizes-row{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:28px;}
.size-chip{min-width:48px;padding:10px 14px;border:1px solid var(--border);background:white;font-size:13px;font-weight:500;color:var(--espresso);cursor:pointer;transition:all 0.12s;text-align:center;}
.size-chip:hover{border-color:var(--espresso);}
.size-chip.selected{background:var(--espresso);color:white;border-color:var(--espresso);}
.size-chip.oos{color:var(--border);cursor:default;text-decoration:line-through;}

/* ── CTA ──────────────────────────────────────────────── */
.btn-wa{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:17px 24px;background:var(--espresso);color:var(--parchment);font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:600;letter-spacing:0.02em;transition:opacity 0.15s;border:none;cursor:pointer;}
.btn-wa:hover{opacity:0.85;}
.btn-oos{display:flex;align-items:center;justify-content:center;width:100%;padding:17px 24px;background:var(--surface);color:var(--muted);font-size:14px;font-weight:500;letter-spacing:0.02em;border:1px solid var(--border);}
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
            if ($product->stock <= 0) { $oos = true; $stockText = 'Out of stock'; $stockClass = 'out'; }
            elseif ($product->stock <= $lowThreshold) { $stockText = 'Only ' . $product->stock . ' left'; $stockClass = 'low'; }
        } elseif ($product->type === 'variant') {
            $totalStock = $product->variants->sum('stock');
            if ($totalStock <= 0) { $oos = true; $stockText = 'Out of stock'; $stockClass = 'out'; }
            elseif ($totalStock <= $lowThreshold) { $stockText = 'Low stock'; $stockClass = 'low'; }
        } elseif ($product->type === 'measured') {
            $remainingMl = $product->bottles->first()?->remaining_ml ?? 0;
            if ($remainingMl <= 0) { $oos = true; $stockText = 'Out of stock'; $stockClass = 'out'; }
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
            Exploring <span class="demo-preview-name">{{ $previewName }}</span> on Stoka
        @else
            You're exploring a demo shop
        @endif
    </span>
    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" class="demo-bar-link">Get your own →</a>
    @if($refBack)<a href="{{ $refBack }}" class="demo-bar-back">← Back</a>@endif
</div>
@endif

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

<div class="breadcrumb">
    <a href="{{ route('shop.index') }}">Shop</a>
    @if($product->category)
    <span class="bc-sep">›</span>
    <a href="{{ route('shop.index', ['cat' => $product->category]) }}">{{ $product->category }}</a>
    @endif
    <span class="bc-sep">›</span>
    <span class="bc-cur">{{ $product->name }}</span>
</div>

<div class="product-page">
    <div class="img-col">
        @if($product->photo)
            <img src="{{ url('/storage/' . $product->photo) }}" alt="{{ $product->name }}" class="hero-photo" loading="eager">
        @else
            <div class="hero-placeholder">{{ mb_strtoupper(mb_substr($product->name, 0, 1)) }}</div>
        @endif
    </div>

    <div class="detail-col">
        @if($product->category)
        <div class="prod-category">{{ $product->category }}</div>
        @endif

        <h1 class="prod-name">{{ $product->name }}</h1>

        <div class="prod-price">
            {{ tenant('currency_symbol') }} {{ number_format((int)$product->shelf_price) }}
            @if($product->type === 'measured')<span class="price-unit">per ml</span>@endif
        </div>

        @if($product->track_stock)
        <div class="avail {{ $stockClass }}">
            <span class="avail-dot"></span>{{ $stockText }}
        </div>
        @endif

        @if($product->description)
        <div class="divider"></div>
        <p class="prod-desc">{{ $product->description }}</p>
        @endif

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

        <div style="margin-top:auto;padding-top:28px;">
            @if($oos)
            <div class="btn-oos">Out of stock</div>
            @elseif($waPhone)
            <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode('Hi, I'd like to ask about ' . $product->name . ' from ' . $tenant->name . '.') }}"
               class="btn-wa" id="waBtn" target="_blank" rel="noopener">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Ask about this
            </a>
            @endif
        </div>
    </div>
</div>

<script>
function selectSize(el) {
    document.querySelectorAll('.size-chip').forEach(function(c) { c.classList.remove('selected'); });
    el.classList.add('selected');
    var btn = document.getElementById('waBtn');
    if (btn) {
        var base = btn.href.split('&text=')[0];
        var msg = 'Hi, I'd like to ask about {{ $product->name }} (size ' + el.dataset.size + ') from {{ $tenant->name }}.';
        btn.href = base + '&text=' + encodeURIComponent(msg);
    }
}
</script>
</body>
</html>