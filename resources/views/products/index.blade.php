@extends('layouts.app')

@section('title', 'Products')

@section('header')
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 class="page-title">Products</h1>
            <p class="page-subtitle">
                {{ $products->count() }} {{ $products->count() === 1 ? 'product' : 'products' }}
            </p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            Add Product
        </a>
    </div>
@endsection

@section('styles')
<style>
    /* ── Responsive toggle ───────────────────────────────── */
    .desktop-table { display: block; }
    .mobile-cards  { display: none; }

    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards  { display: block; }
    }

    /* ── Desktop table ───────────────────────────────────── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 12px 20px;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .data-table td {
        padding: 14px 20px;
        font-size: 13.5px;
        color: var(--espresso);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover td { background: #FAF5EF; }
    .product-name     { font-weight: 600; }
    .product-category { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .td-muted   { color: var(--muted); }
    .td-actions { width: 1%; white-space: nowrap; text-align: right; }

    /* ── Type badges ─────────────────────────────────────── */
    .ptype-unit     { background: #E3EDDF; color: #4A6741; }
    .ptype-variant  { background: #F5E8DC; color: #C17F4A; }
    .ptype-measured { background: #F5DDD8; color: #B85C38; }

    /* ── Stock colours ───────────────────────────────────── */
    .stock-num  { font-family: "DM Mono", monospace; font-size: 13px; }
    .stock-zero { color: var(--clay); }
    .stock-low  { color: var(--terracotta); }
    .stock-ok   { color: var(--espresso); }

    /* ── Status toggle ───────────────────────────────────── */
    .status-btn {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: var(--radius-full);
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        font-family: "Plus Jakarta Sans", sans-serif;
        transition: opacity 0.13s;
        line-height: 1.4;
    }
    .status-btn:hover  { opacity: 0.75; }
    .status-active     { background: #DFF0DD; color: #2E6B35; }
    .status-inactive   { background: #F0EDE8; color: var(--muted); }

    /* ── Row action button ───────────────────────────────── */
    .btn-row {
        background: none;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 5px 11px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        font-family: "Plus Jakarta Sans", sans-serif;
        transition: background 0.13s, border-color 0.13s, color 0.13s;
        line-height: 1.4;
        text-decoration: none;
        color: var(--mid);
        display: inline-block;
    }
    .btn-row:hover {
        background: #F0E8DC;
        border-color: var(--terracotta);
        color: var(--terracotta);
    }

    .price-cell { font-family: "DM Mono", monospace; font-size: 13px; }

    /* ── Mobile cards ────────────────────────────────────── */
    .m-card {
        background: var(--parchment);
        border: 1px solid var(--border);
        border-radius: var(--radius-default);
        padding: 16px;
        margin-bottom: 10px;
    }
    .m-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }
    .m-card-name     { font-weight: 600; font-size: 15px; color: var(--espresso); line-height: 1.3; }
    .m-card-category { font-size: 12px; color: var(--muted); margin-top: 3px; }
    .m-card-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }
    .m-card-price {
        font-family: "DM Mono", monospace;
        font-size: 14px;
        color: var(--terracotta);
        font-weight: 500;
    }
    .m-card-supplier { font-size: 12.5px; color: var(--muted); margin-bottom: 12px; }
    .m-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    /* ── Flash ───────────────────────────────────────────── */
    .flash-success {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #DFF0DD;
        border: 1px solid #BFD9BC;
        border-radius: var(--radius-md);
        padding: 12px 16px;
        margin-bottom: 22px;
        font-size: 13.5px;
        color: var(--forest);
        font-weight: 500;
    }

    /* ── Empty state ─────────────────────────────────────── */
    .empty-state { text-align: center; padding: 80px 24px; }
    .empty-icon  { width: 44px; height: 44px; margin: 0 auto 18px; color: var(--border); }
    .empty-title { font-family: "Cormorant Garamond", serif; font-size: 22px; font-weight: 600; color: var(--mid); margin-bottom: 8px; }
    .empty-text  { font-size: 13px; color: var(--muted); margin-bottom: 24px; }
</style>
@endsection

@section('content')

    @if(session('success'))
    <div class="flash-success">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
            <path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($products->isEmpty())
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 44 44" fill="none">
                <rect x="2" y="2" width="40" height="40" rx="10" stroke="currentColor" stroke-width="2"/>
                <path d="M14 22h16M22 14v16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <p class="empty-title">Your shelves are empty</p>
            <p class="empty-text">Add everything you sell — clothes, accessories, fragrances, all of it. Start with your bestsellers.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add your first item</a>
        </div>
    @else

        {{-- ── Desktop table (≥768px) ──────────────────────── --}}
        <div class="desktop-table card" style="padding:0; overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    @php
                        if ($product->type === 'measured') {
                            $rem = $product->bottles->where('active', true)->sum('remaining_ml');
                            $stockDisplay = $rem > 0 ? number_format($rem, 1) . ' ml' : '—';
                            $stockClass   = '';
                        } elseif (!$product->track_stock) {
                            $stockDisplay = '—';
                            $stockClass   = '';
                        } elseif ($product->type === 'variant') {
                            $totalStock   = $product->variants->sum('stock');
                            $stockDisplay = $totalStock;
                            $stockClass   = $totalStock == 0 ? 'stock-zero' : ($totalStock <= $product->low_stock_threshold ? 'stock-low' : 'stock-ok');
                        } else {
                            $stockDisplay = $product->stock;
                            $stockClass   = $product->stock == 0 ? 'stock-zero' : ($product->stock <= $product->low_stock_threshold ? 'stock-low' : 'stock-ok');
                        }
                        $typeLabels = ['unit' => 'Unit', 'measured' => 'Measured', 'variant' => 'Variant'];
                        $typeBadge  = ['unit' => 'ptype-unit', 'measured' => 'ptype-measured', 'variant' => 'ptype-variant'];
                    @endphp
                    <tr>
                        <td>
                            <div class="product-name">{{ $product->name }}</div>
                            @if($product->category)
                                <div class="product-category">{{ $product->category }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $typeBadge[$product->type] ?? '' }}">
                                {{ $typeLabels[$product->type] ?? $product->type }}
                            </span>
                        </td>
                        <td class="td-muted">{{ $product->supplier?->name ?? '—' }}</td>
                        <td class="price-cell">{{ tenant(currency_symbol) }} {{ number_format($product->shelf_price, 2) }}</td>
                        <td><span class="stock-num {{ $stockClass }}">{{ $stockDisplay }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('products.toggle', $product) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="status-btn {{ $product->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $product->status === 'active' ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="td-actions">
                            <a href="{{ route('products.edit', $product) }}" class="btn-row">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ── Mobile cards (<768px) ───────────────────────── --}}
        <div class="mobile-cards">
            @foreach($products as $product)
            @php
                if ($product->type === 'measured') {
                    $rem = $product->bottles->where('active', true)->sum('remaining_ml');
                    $stockDisplay = $rem > 0 ? number_format($rem, 1) . ' ml' : '—';
                    $stockClass   = '';
                } elseif (!$product->track_stock) {
                    $stockDisplay = '—';
                    $stockClass   = '';
                } elseif ($product->type === 'variant') {
                    $totalStock   = $product->variants->sum('stock');
                    $stockDisplay = $totalStock;
                    $stockClass   = $totalStock == 0 ? 'stock-zero' : ($totalStock <= $product->low_stock_threshold ? 'stock-low' : 'stock-ok');
                } else {
                    $stockDisplay = $product->stock;
                    $stockClass   = $product->stock == 0 ? 'stock-zero' : ($product->stock <= $product->low_stock_threshold ? 'stock-low' : 'stock-ok');
                }
                $typeLabels = ['unit' => 'Unit', 'measured' => 'Measured', 'variant' => 'Variant'];
                $typeBadge  = ['unit' => 'ptype-unit', 'measured' => 'ptype-measured', 'variant' => 'ptype-variant'];
            @endphp
            <div class="m-card">
                {{-- Name + status --}}
                <div class="m-card-header">
                    <div>
                        <div class="m-card-name">{{ $product->name }}</div>
                        @if($product->category)
                            <div class="m-card-category">{{ $product->category }}</div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('products.toggle', $product) }}" style="flex-shrink:0;">
                        @csrf
                        <button type="submit" class="status-btn {{ $product->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ $product->status === 'active' ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                </div>

                {{-- Type badge + price --}}
                <div class="m-card-meta">
                    <span class="badge {{ $typeBadge[$product->type] ?? '' }}">
                        {{ $typeLabels[$product->type] ?? $product->type }}
                    </span>
                    <span class="m-card-price">{{ tenant(currency_symbol) }} {{ number_format($product->shelf_price, 2) }}</span>
                </div>

                {{-- Supplier --}}
                @if($product->supplier)
                    <div class="m-card-supplier">{{ $product->supplier->name }}</div>
                @endif

                {{-- Stock + edit --}}
                <div class="m-card-footer">
                    <span class="stock-num {{ $stockClass }}">
                        @if($stockClass || $stockDisplay !== '—')
                            Stock: {{ $stockDisplay }}
                        @else
                            <span style="color:var(--muted);">—</span>
                        @endif
                    </span>
                    <a href="{{ route('products.edit', $product) }}" class="btn-row">Edit</a>
                </div>
            </div>
            @endforeach
        </div>

    @endif

@endsection
