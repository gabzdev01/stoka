@extends('layouts.app')

@section('title', 'Shopping List')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Shopping list</h1>
        <p class="page-subtitle">
            Stock levels as of now ·
            @if($outCount > 0)
                <span style="color:var(--clay);font-weight:600;">{{ $outCount }} sold out</span>
                @if($lowCount > 0) · @endif
            @endif
            @if($lowCount > 0)
                <span style="color:var(--terracotta);font-weight:600;">{{ $lowCount }} running low</span>
            @endif
            @if($outCount === 0 && $lowCount === 0)
                <span style="color:var(--forest);font-weight:600;">All stock healthy ✓</span>
            @endif
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        {{-- Supplier filter --}}
        @if($suppliers->isNotEmpty())
        <select id="supplier-filter" onchange="filterSupplier(this.value)"
                style="height:38px;padding:0 12px;border:1px solid var(--border);border-radius:8px;
                       font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;
                       color:var(--espresso);background:var(--surface);cursor:pointer;outline:none;">
            <option value="">All suppliers</option>
            <option value="none">No supplier</option>
            @foreach($suppliers as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
        @endif
        {{-- WhatsApp send (Phase 7) --}}
        <button onclick="alert('WhatsApp sending coming soon.')"
                style="height:38px;padding:0 16px;background:var(--surface);border:1px solid var(--border);
                       border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;
                       font-weight:600;color:var(--muted);cursor:pointer;display:flex;align-items:center;gap:7px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.854L0 24l6.335-1.51A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.007-1.374l-.36-.214-3.724.887.924-3.614-.235-.372A9.818 9.818 0 1112 21.818z"/>
            </svg>
            Send via WhatsApp
        </button>
    </div>
</div>
@endsection

@section('styles')
<style>
/* ── Suggest prompt card ────────────────────────── */
.suggest-prompt {
    position: relative;
    margin-bottom: 32px;
    padding: 30px 32px;
    background: var(--espresso);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}
.suggest-prompt::after {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(193,127,74,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.suggest-prompt-body {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    position: relative;
    z-index: 1;
}
.suggest-prompt-icon {
    width: 40px; height: 40px;
    flex-shrink: 0;
    background: rgba(193,127,74,0.15);
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    color: #C17F4A;
}
.suggest-prompt-copy {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.suggest-prompt-headline {
    font-family: "Cormorant Garamond", serif;
    font-size: 22px;
    font-weight: 600;
    color: #FAF7F2;
    line-height: 1.15;
    letter-spacing: 0.01em;
}
.suggest-prompt-sub {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13px;
    line-height: 1.5;
    color: rgba(250,247,242,0.5);
}
.suggest-prompt-btn {
    position: relative;
    z-index: 1;
    flex-shrink: 0;
    height: 42px;
    padding: 0 22px;
    background: #C17F4A;
    color: #FAF7F2;
    border: none;
    border-radius: 10px;
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: 0.02em;
    transition: opacity 0.14s, transform 0.14s;
    white-space: nowrap;
    box-shadow: 0 2px 12px rgba(193,127,74,0.35);
}
.suggest-prompt-btn:hover { opacity: 0.88; transform: translateY(-1px); }
.suggest-prompt-btn:active { opacity: 0.78; transform: translateY(0); }
@media (max-width: 540px) {
    .suggest-prompt { padding: 22px 20px; }
    .suggest-prompt-headline { font-size: 19px; }
    .suggest-prompt-btn { width: 100%; justify-content: center; display: flex; align-items: center; }
}

/* ── Stock summary strip (replaces legend) ───────── */
.stock-strip {
    display: flex;
    gap: 0;
    margin-bottom: 24px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}
.stock-strip-item {
    flex: 1;
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    position: relative;
}
.stock-strip-item + .stock-strip-item { border-left: 1px solid var(--border); }
.strip-count {
    font-family: "DM Mono", monospace;
    font-size: 20px;
    font-weight: 500;
}
.strip-count.out   { color: var(--clay); }
.strip-count.low   { color: var(--terracotta); }
.strip-count.ok    { color: var(--forest); }
.strip-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

/* ── Suggest hidden state ───────────────────────── */
.suggest-hidden .sl-suggest-col,
.suggest-hidden .h-suggest { display: none; }

/* ── Legend ─────────────────────────────────────── */
.legend {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--muted);
}
.leg-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.leg-out  { background: var(--clay); }
.leg-low  { background: var(--terracotta); }
.leg-ok   { background: var(--forest); }

/* ── Category section ───────────────────────────── */
.cat-section {
    margin-bottom: 28px;
}
.cat-title {
    font-size: 11px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.09em;
    margin-bottom: 10px; display: block;
}

/* ── Product rows ───────────────────────────────── */
.product-rows {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}

/* ── Unit / measured row ────────────────────────── */
.sl-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    border-bottom: 1px solid #F5F0EB;
    transition: background 0.12s;
}
.sl-row:last-child { border-bottom: none; }
.sl-row:hover { background: #FDFAF7; }

/* ── Supplier hidden for filter ─────────────────── */
.sl-row[data-supplier].hidden,
.variant-group[data-supplier].hidden { display: none; }

.sl-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.dot-out  { background: var(--clay); }
.dot-low  { background: var(--terracotta); }
.dot-ok   { background: var(--forest); }

.sl-name {
    flex: 1; min-width: 0;
    font-size: 14px; font-weight: 500; color: var(--espresso);
}
.sl-supplier {
    font-size: 11.5px; color: var(--muted); margin-top: 2px;
}

/* ── Stock display ──────────────────────────────── */
.sl-stock-col {
    text-align: right; flex-shrink: 0; min-width: 80px;
}
.sl-stock-num {
    font-family: "DM Mono", monospace;
    font-size: 15px; font-weight: 500;
}
.stock-out { color: var(--clay); }
.stock-low { color: var(--terracotta); }
.stock-ok  { color: var(--forest); }
.sl-stock-label {
    font-size: 11px; color: var(--muted); margin-top: 1px;
}

/* ── Velocity ───────────────────────────────────── */
.sl-vel-col {
    text-align: right; flex-shrink: 0; min-width: 70px;
}
.sl-vel-num {
    font-family: "DM Mono", monospace;
    font-size: 13px; color: var(--muted);
}
.sl-vel-num.fast { color: var(--terracotta); font-weight: 600; }
.sl-vel-label {
    font-size: 11px; color: var(--muted); margin-top: 1px;
}

/* ── Suggest ────────────────────────────────────── */
.sl-suggest-col {
    text-align: right; flex-shrink: 0; min-width: 80px;
}
.sl-suggest-num {
    font-family: "DM Mono", monospace;
    font-size: 15px; font-weight: 600;
    color: var(--espresso);
}
.sl-suggest-num.none { color: var(--muted); font-weight: 400; }
.sl-suggest-label {
    font-size: 11px; color: var(--muted); margin-top: 1px;
}

/* ── Column headers ─────────────────────────────── */
.sl-headers {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 0 18px 8px;
    font-size: 10px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.08em;
}
.sl-headers .h-name    { flex: 1; }
.sl-headers .h-stock   { min-width: 80px; text-align: right; }
.sl-headers .h-vel     { min-width: 70px; text-align: right; }
.sl-headers .h-suggest { min-width: 80px; text-align: right; }

/* ── Variant group ──────────────────────────────── */
.variant-group {
    border-bottom: 1px solid #F5F0EB;
}
.variant-group:last-child { border-bottom: none; }

.variant-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 18px 8px;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
}
.variant-header:hover { background: #FDFAF7; }
.variant-product-name {
    flex: 1; min-width: 0;
    font-size: 14px; font-weight: 500; color: var(--espresso);
}
.variant-chevron {
    color: var(--muted);
    transition: transform 0.2s;
    flex-shrink: 0;
}
.variant-group.open .variant-chevron { transform: rotate(180deg); }

.variant-rows {
    display: none;
    padding: 0 0 8px;
}
.variant-group.open .variant-rows { display: block; }

.vr-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 7px 18px 7px 38px;
}
.vr-row:hover { background: #FDFAF7; }
.vr-size {
    flex: 1; min-width: 0;
    font-size: 13px; color: var(--muted); font-weight: 500;
}

/* ── Bottle row special ─────────────────────────── */
.bottle-bar-wrap {
    flex: 1; display: flex; align-items: center; gap: 10px; min-width: 0;
}
.bottle-bar-track {
    flex: 1; height: 6px; background: #EDE8E0;
    border-radius: 3px; overflow: hidden; min-width: 40px;
}
.bottle-bar-fill {
    height: 100%; border-radius: 3px; transition: width 0.3s;
}
.fill-ok  { background: var(--forest); }
.fill-low { background: var(--terracotta); }
.fill-out { background: var(--clay); }
.bottle-ml {
    font-family: "DM Mono", monospace;
    font-size: 12px; color: var(--muted); white-space: nowrap;
}

/* ── Mobile: hide velocity column ───────────────── */
@media (max-width: 600px) {
    .sl-vel-col, .sl-headers .h-vel { display: none; }
    .sl-headers .h-dot { display: none; }
}
</style>
@endsection

@section('content')

{{-- Suggest reveal prompt --}}
<div id="suggest-prompt" class="suggest-prompt">
    <div class="suggest-prompt-body">
        <div class="suggest-prompt-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
        </div>
        <div class="suggest-prompt-copy">
            <span class="suggest-prompt-headline">Your stock, analyzed.</span>
            <span class="suggest-prompt-sub">Ready to show what needs restocking based on the last 30 days of sales.</span>
        </div>
    </div>
    <button class="suggest-prompt-btn" onclick="revealSuggest()">Show suggestions &rarr;</button>
</div>

<div id="shopping-wrap" class="suggest-hidden">

{{-- Stock summary strip --}}
<div class="stock-strip">
    <div class="stock-strip-item">
        <span class="strip-count out">{{ $outCount }}</span>
        <span class="strip-label">Sold out</span>
    </div>
    <div class="stock-strip-item">
        <span class="strip-count low">{{ $lowCount }}</span>
        <span class="strip-label">Running low</span>
    </div>
    <div class="stock-strip-item">
        <span class="strip-count ok">{{ $products->count() - $outCount - $lowCount }}</span>
        <span class="strip-label">Healthy</span>
    </div>
</div>

@foreach($byCategory as $category => $catProducts)
<div class="cat-section">
    <span class="cat-title">{{ $category ?: 'Uncategorised' }}</span>

    {{-- Column headers --}}
    <div class="sl-headers">
        <span style="width:8px;flex-shrink:0;"></span>
        <span class="h-name">Product</span>
        <span class="h-vel">30d sold</span>
        <span class="h-stock">In stock</span>
        <span class="h-suggest">Suggest</span>
    </div>

    <div class="product-rows">
    @foreach($catProducts as $product)
    @php
        $suppId  = $product->supplier_id ?? 'none';
        $suppName = $product->supplier?->name;
        $status  = $product->stock_status;
        $dotClass = 'dot-' . $status;
        $suggest  = (int) $product->suggest_qty;
        $vel      = (float) $product->sold_30d;
        $isFast   = $vel >= 10;
    @endphp

    @if($product->type === 'unit')
    {{-- Unit product row --}}
    <div class="sl-row" data-supplier="{{ $suppId }}">
        <span class="sl-dot {{ $dotClass }}"></span>
        <div class="sl-name">
            {{ $product->name }}
            @if($suppName)<span class="sl-supplier">{{ $suppName }}</span>@endif
        </div>
        <div class="sl-vel-col">
            <div class="sl-vel-num {{ $isFast ? 'fast' : '' }}">{{ number_format($vel, 0) }}</div>
            <div class="sl-vel-label">sold</div>
        </div>
        <div class="sl-stock-col">
            <div class="sl-stock-num stock-{{ $status }}">{{ $product->stock }}</div>
            <div class="sl-stock-label">in stock</div>
        </div>
        <div class="sl-suggest-col">
            <div class="sl-suggest-num {{ $suggest == 0 ? 'none' : '' }}">
                {{ $suggest > 0 ? $suggest : '—' }}
            </div>
            <div class="sl-suggest-label">to buy</div>
        </div>
    </div>

    @elseif($product->type === 'measured')
    {{-- Measured product row --}}
    @php
        $bottle = $product->bottle;
        $pct    = $bottle ? min(1, (float)$bottle->remaining_ml / max(1, (float)$bottle->total_ml)) : 0;
        $pctPx  = round($pct * 100);
        $fillCls= $status === 'out' ? 'fill-out' : ($status === 'low' ? 'fill-low' : 'fill-ok');
        $mlText = $bottle
            ? number_format((float)$bottle->remaining_ml, 0) . ' / ' . number_format((float)$bottle->total_ml, 0) . 'ml'
            : 'No active bottle';
    @endphp
    <div class="sl-row" data-supplier="{{ $suppId }}">
        <span class="sl-dot {{ $dotClass }}"></span>
        <div class="sl-name">
            {{ $product->name }}
            @if($suppName)<span class="sl-supplier">{{ $suppName }}</span>@endif
        </div>
        <div class="sl-vel-col">
            <div class="sl-vel-num {{ $isFast ? 'fast' : '' }}">{{ number_format($vel, 0) }}ml</div>
            <div class="sl-vel-label">sold</div>
        </div>
        <div class="bottle-bar-wrap" style="flex:1;min-width:80px;">
            <div class="bottle-bar-track">
                <div class="bottle-bar-fill {{ $fillCls }}" style="width:{{ $pctPx }}%;"></div>
            </div>
            <span class="bottle-ml">{{ $mlText }}</span>
        </div>
        <div class="sl-suggest-col">
            <div class="sl-suggest-num none">New bottle</div>
            <div class="sl-suggest-label">if low</div>
        </div>
    </div>

    @elseif($product->type === 'variant')
    {{-- Variant product — collapsible --}}
    <div class="variant-group" data-supplier="{{ $suppId }}" id="vg-{{ $product->id }}">
        <div class="variant-header" onclick="toggleVariant({{ $product->id }})">
            <span class="sl-dot {{ $dotClass }}"></span>
            <div class="variant-product-name">
                {{ $product->name }}
                @if($suppName)<span class="sl-supplier" style="font-size:11.5px;color:var(--muted);display:block;">{{ $suppName }}</span>@endif
            </div>
            <div class="sl-vel-col">
                <div class="sl-vel-num {{ $isFast ? 'fast' : '' }}">{{ number_format($vel, 0) }}</div>
                <div class="sl-vel-label">sold</div>
            </div>
            <div class="sl-stock-col" style="font-size:12px;color:var(--muted);">
                {{ $product->variants->count() }} sizes
            </div>
            <div class="sl-suggest-col">
                <div class="sl-suggest-num {{ $suggest == 0 ? 'none' : '' }}">
                    {{ $suggest > 0 ? $suggest : '—' }}
                </div>
                <div class="sl-suggest-label">total</div>
            </div>
            <svg class="variant-chevron" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M3 5l4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="variant-rows">
            @foreach($product->variants as $variant)
            @php
                $vs      = $variant->stock_status;
                $vsug    = (int) $variant->suggest_qty;
                $vvel    = (float) $variant->sold_30d;
                $label   = trim(($variant->size ?? '') . ' ' . ($variant->colour ?? ''));
            @endphp
            <div class="vr-row">
                <span class="sl-dot dot-{{ $vs }}"></span>
                <div class="vr-size">{{ $label ?: 'Default' }}</div>
                <div class="sl-vel-col">
                    <div class="sl-vel-num">{{ number_format($vvel, 0) }}</div>
                    <div class="sl-vel-label">sold</div>
                </div>
                <div class="sl-stock-col">
                    <div class="sl-stock-num stock-{{ $vs }}">{{ $variant->stock }}</div>
                    <div class="sl-stock-label">in stock</div>
                </div>
                <div class="sl-suggest-col">
                    <div class="sl-suggest-num {{ $vsug == 0 ? 'none' : '' }}">
                        {{ $vsug > 0 ? $vsug : '—' }}
                    </div>
                    <div class="sl-suggest-label">to buy</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endforeach
    </div>
</div>
@endforeach

</div>{{-- /shopping-wrap --}}

@endsection

@section('scripts')
<script>
function toggleVariant(id) {
    var grp = document.getElementById('vg-' + id);
    if (grp) grp.classList.toggle('open');
}

function filterSupplier(suppId) {
    document.querySelectorAll('[data-supplier]').forEach(function(el) {
        if (!suppId || el.dataset.supplier === suppId) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    });
    // Hide empty category sections
    document.querySelectorAll('.cat-section').forEach(function(sec) {
        var anyVisible = sec.querySelectorAll('[data-supplier]:not(.hidden)').length > 0;
        sec.style.display = anyVisible ? '' : 'none';
    });
}
function revealSuggest() {
    var wrap   = document.getElementById('shopping-wrap');
    var prompt = document.getElementById('suggest-prompt');
    if (wrap) wrap.classList.remove('suggest-hidden');
    if (prompt) prompt.style.display = 'none';
    try { sessionStorage.setItem('stoka_suggest_revealed', '1'); } catch(e) {}
}

// Auto-reveal if previously shown in this session
if (sessionStorage.getItem('stoka_suggest_revealed') === '1') {
    revealSuggest();
}
</script>
@endsection
