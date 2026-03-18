@extends('layouts.staff')

@section('title', 'Products')

@section('styles')
<style>

    /* ════════════════════════════════════════════════
       CATEGORY TAB STRIP
    ════════════════════════════════════════════════ */

    .cat-strip-outer {
        position: sticky;
        top: var(--top-h);
        z-index: 100;
        background: var(--darker-wood);
        /* pull to full width, cancel content-inner side + top padding */
        margin: -20px -16px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .cat-strip {
        display: flex;
        align-items: center;
        gap: 4px;
        overflow-x: auto;
        overflow-y: visible;
        padding: 8px 12px 10px;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        /* hide scrollbar: Firefox */
        scrollbar-width: none;
    }
    /* hide scrollbar: WebKit */
    .cat-strip::-webkit-scrollbar { display: none; }

    .cat-tab {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        height: 34px;
        min-height: 44px;              /* touch target */
        padding: 0 16px;
        border-radius: 20px;
        border: none;
        background: transparent;
        color: #8C7B6E;                /* muted */
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.14s ease, color 0.14s ease;
        -webkit-tap-highlight-color: transparent;
        letter-spacing: 0.01em;
    }

    .cat-tab.active {
        background: #C17F4A;           /* terracotta */
        color: #fff;
    }

    .cat-tab:active { opacity: 0.78; }

    /* ════════════════════════════════════════════════
       PAGE BACKGROUND
    ════════════════════════════════════════════════ */

    /* Override content-inner background so the whole scrollable
       area reads as warm parchment */
    .staff-content,
    .content-inner {
        background: #FAF7F2;
    }

    /* ════════════════════════════════════════════════
       RESPONSIVE PRODUCT GRID
    ════════════════════════════════════════════════ */

    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    @media (min-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
    }

    @media (min-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
    }

    /* ════════════════════════════════════════════════
       PRODUCT CARD
    ════════════════════════════════════════════════ */

    .product-card {
        background: #FFFFFF;
        border: 1px solid #E8E0D6;
        border-radius: 14px;
        padding: 20px;
        text-align: left;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-family: "Plus Jakarta Sans", sans-serif;
        box-shadow: 0 1px 3px rgba(28, 24, 20, 0.08),
                    0 4px 12px rgba(28, 24, 20, 0.04);
        transition: box-shadow 0.15s ease, transform 0.15s ease,
                    background 0.12s ease;
        -webkit-tap-highlight-color: transparent;
        width: 100%;
    }

    .product-card:hover {
        box-shadow: 0 2px 8px rgba(28, 24, 20, 0.12),
                    0 8px 24px rgba(28, 24, 20, 0.08);
        transform: translateY(-1px);
    }

    .product-card:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(28, 24, 20, 0.08);
        background: #FAF7F2;
    }

    .product-card.out-of-stock {
        opacity: 0.5;
        cursor: default;
    }

    .product-card.out-of-stock:hover {
        box-shadow: 0 1px 3px rgba(28, 24, 20, 0.08),
                    0 4px 12px rgba(28, 24, 20, 0.04);
        transform: none;
    }

    /* hidden by filter */
    .product-card.hidden { display: none; }

    /* ── Product name ────────────────────────────── */
    .product-name {
        font-size: 15px;
        font-weight: 600;
        color: #1C1814;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ── Gender badge ────────────────────────────── */
    .gender-badge {
        display: inline-flex;
        align-items: center;
        font-size: 10px;
        font-weight: 600;
        border-radius: 10px;
        padding: 2px 8px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        white-space: nowrap;
        align-self: flex-start;
    }
    .gender-male   { background: #EBF0F5; color: #4A6080; }
    .gender-female { background: #F5EBF0; color: #7A4A65; }
    .gender-unisex { background: #EBF0EB; color: #4A6741; }

    /* ── Price + stock row ───────────────────────── */
    .product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: auto;          /* pushes to card bottom */
    }

    .product-price {
        font-family: "DM Mono", monospace;
        font-size: 17px;
        font-weight: 500;
        color: #C17F4A;
        line-height: 1;
    }

    .stock-badge {
        font-size: 11px;
        font-weight: 600;
        border-radius: 20px;
        padding: 3px 10px;
        white-space: nowrap;
    }
    .stock-ok  { background: #F2EDE6; color: #8C7B6E; }
    .stock-low { background: #F5EADB; color: #A06020; }
    .stock-out { background: #F5DDD8; color: #B85C38; }

    .bargain-hint {
        font-size: 10px;
        font-weight: 600;
        color: #C17F4A;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-top: 2px;
    }

    /* ════════════════════════════════════════════════
       NO-RESULTS STATE
    ════════════════════════════════════════════════ */

    .no-results {
        display: none;
        flex-direction: column;
        align-items: center;
        padding: 64px 24px;
        text-align: center;
        gap: 12px;
    }
    .no-results.visible   { display: flex; }
    .no-results-icon      { color: #D9CEBC; }
    .no-results-text      { font-size: 15px; font-weight: 600; color: #8C7B6E; }
    .no-results-query     { font-size: 13px; color: #C4B9A8; font-weight: 400; }

</style>
@endsection

@section('content')

{{-- ── Category tab strip (sticky, dark, full-bleed) ─ --}}
<div class="cat-strip-outer">
    <div class="cat-strip" id="cat-strip">
        {{-- Built by JS --}}
    </div>
</div>

{{-- ── Section label ──────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin:20px 0 14px;">
    <span style="font-size:11px;font-weight:700;color:#8C7B6E;text-transform:uppercase;letter-spacing:0.1em;">
        All Products
    </span>
    <span id="product-count" style="font-family:'DM Mono',monospace;font-size:11px;color:#8C7B6E;">
        6 items
    </span>
</div>

{{-- ── Product grid ─────────────────────────────── --}}
<div class="product-grid" id="product-grid">

    @foreach([
        ['Linen Dress',        'Ksh 1,800', '4 in stock',   false, 'Dresses',   'female'],
        ['Body Mist 200ml',    'Ksh 650',   '2 in stock',   false, 'Perfumes',  null],
        ['Denim Jacket',       'Ksh 3,200', '1 in stock',   false, 'Jackets',   'male'],
        ['Gold Hoop Earrings', 'Ksh 450',   '8 in stock',   false, 'Jewellery', null],
        ['Leather Handbag',    'Ksh 2,500', '3 in stock',   true,  'Bags',      'female'],
        ['Floral Blouse S',    'Ksh 900',   'Out of stock', false, 'Tops',      'female'],
    ] as [$name, $price, $stock, $bargainable, $category, $gender])

    @php
        $isOut = $stock === 'Out of stock';
        $isLow = $stock === '1 in stock';
        $stockClass  = $isOut ? 'stock-out' : ($isLow ? 'stock-low' : 'stock-ok');
        $genderLabel = ['male' => "Men's", 'female' => "Women's", 'unisex' => 'Unisex'];
    @endphp

    <button
        class="product-card{{ $isOut ? ' out-of-stock' : '' }}"
        data-search="{{ strtolower($name . ' ' . $category) }}"
        data-category="{{ $category }}"
    >
        <span class="product-name">{{ $name }}</span>

        @if($gender)
            <span class="gender-badge gender-{{ $gender }}">{{ $genderLabel[$gender] }}</span>
        @endif

        <div class="product-meta">
            <span class="product-price">{{ $price }}</span>
            <span class="stock-badge {{ $stockClass }}">{{ $stock }}</span>
        </div>

        @if($bargainable)
            <span class="bargain-hint">Bargainable</span>
        @endif
    </button>

    @endforeach

</div>

{{-- ── No results ───────────────────────────────── --}}
<div class="no-results" id="no-results">
    <svg class="no-results-icon" width="44" height="44" viewBox="0 0 44 44" fill="none">
        <circle cx="20" cy="20" r="12" stroke="currentColor" stroke-width="1.8"/>
        <path d="M29 29L39 39" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M14.5 20h11M20 14.5v11" stroke="currentColor" stroke-width="1.6"
              stroke-linecap="round" opacity="0.35"/>
    </svg>
    <span class="no-results-text">No products found</span>
    <span class="no-results-query" id="no-results-query"></span>
</div>

<div style="height:32px;"></div>

@endsection

@section('scripts')
<script>
(function () {
    var input     = document.getElementById('staff-search');
    var grid      = document.getElementById('product-grid');
    var countEl   = document.getElementById('product-count');
    var noResults = document.getElementById('no-results');
    var noQuery   = document.getElementById('no-results-query');
    var catStrip  = document.getElementById('cat-strip');
    var cards     = Array.prototype.slice.call(grid.querySelectorAll('.product-card'));
    var total     = cards.length;
    var activeCat = '';   // '' = All

    // ── Build category tabs ─────────────────────────
    var seen = {};
    var cats = [];
    cards.forEach(function (card) {
        var cat = card.dataset.category || '';
        if (cat && !seen[cat]) {
            var hasInStock = cards.some(function (c) {
                return c.dataset.category === cat &&
                       !c.classList.contains('out-of-stock');
            });
            if (hasInStock) {
                seen[cat] = true;
                cats.push(cat);
            }
        }
    });

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
            applyFilters();
        });
        return btn;
    }

    catStrip.appendChild(makeTab('All', ''));
    cats.forEach(function (cat) { catStrip.appendChild(makeTab(cat, cat)); });

    if (cats.length === 0) {
        catStrip.closest('.cat-strip-outer').style.display = 'none';
    }

    // ── Combined search + category filter ──────────
    function applyFilters() {
        var q          = input.value.trim().toLowerCase();
        var isFiltered = q || activeCat;
        var visible    = 0;

        cards.forEach(function (card) {
            var matchSearch = !q         || card.dataset.search.indexOf(q) !== -1;
            var matchCat    = !activeCat || card.dataset.category === activeCat;
            card.classList.toggle('hidden', !(matchSearch && matchCat));
            if (matchSearch && matchCat) visible++;
        });

        countEl.textContent = isFiltered
            ? visible + (visible === 1 ? ' result' : ' results')
            : total + ' items';

        if (visible === 0 && isFiltered) {
            noResults.classList.add('visible');
            noQuery.textContent = q ? '\u201C' + input.value.trim() + '\u201D' : '';
        } else {
            noResults.classList.remove('visible');
            noQuery.textContent = '';
        }
    }

    input.addEventListener('input', applyFilters);
}());
</script>
@endsection
