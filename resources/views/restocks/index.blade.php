@extends('layouts.app')

@section('title', 'Restocks')

@section('header')
<div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
    <div>
        <h1 class="page-title">Restocks</h1>
        <p class="page-subtitle">
            {{ $restocks->count() }} {{ $restocks->count() === 1 ? 'restock' : 'restocks' }} recorded
        </p>
    </div>
    <a href="{{ route('restocks.create') }}" class="btn btn-primary">+ New Restock</a>
</div>
@endsection

@section('styles')
<style>
.restock-table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(28,24,20,0.05);
}
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    font-size: 11px; font-weight: 600; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.07em;
    padding: 11px 18px; text-align: left;
    border-bottom: 1px solid var(--border); white-space: nowrap;
}
.data-table td {
    padding: 12px 18px; font-size: 13.5px; color: var(--espresso);
    border-bottom: 1px solid var(--border); vertical-align: middle;
}
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #FDFAF7; }
.td-mono { font-family: "DM Mono", monospace; }
.td-muted { color: var(--muted); font-size: 12.5px; }

/* Mobile cards */
.desktop-restocks { display: block; }
.mobile-restocks   { display: none; }
@media (max-width: 767px) {
    .desktop-restocks { display: none; }
    .mobile-restocks   { display: block; }
}
.restock-card-m {
    background: #fff;
    border-bottom: 1px solid var(--border);
    padding: 14px 16px;
}
.restock-card-m:first-child { border-radius: 14px 14px 0 0; }
.restock-card-m:last-child  { border-bottom: none; border-radius: 0 0 14px 14px; }
.rcm-top  { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.rcm-sup  { font-size: 15px; font-weight: 600; color: var(--espresso); }
.rcm-date { font-size: 12px; color: var(--muted); margin-top: 2px; }
.rcm-cost { font-family: "DM Mono", monospace; font-size: 15px; font-weight: 500; }
.rcm-meta { font-size: 12px; color: var(--muted); margin-top: 6px; font-family: "DM Mono", monospace; }

/* Empty */
.empty-state {
    text-align: center; padding: 60px 20px; color: var(--muted); font-size: 14px; line-height: 1.7;
}
.empty-title {
    font-family: "Cormorant Garamond", serif; font-size: 22px; font-weight: 600;
    color: var(--espresso); margin-bottom: 8px;
}

/* Flash */
.restock-flash {
    display: flex; align-items: center; gap: 10px;
    background: #DFF0DD; color: var(--forest);
    border-radius: 12px; padding: 12px 16px;
    font-size: 14px; font-weight: 600; margin-bottom: 20px;
}
</style>
@endsection

@section('content')

@if(session('success'))
<div class="restock-flash">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="flex-shrink:0">
        <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/>
        <path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if($restocks->isEmpty())
<div class="restock-table-wrap">
    <div class="empty-state">
        <p class="empty-title">No restocks yet</p>
        <p>Record your first restock to start tracking stock levels and supplier balances.</p>
        <a href="{{ route('restocks.create') }}" class="btn btn-primary" style="display:inline-block;margin-top:16px;">+ New Restock</a>
    </div>
</div>
@else

{{-- Desktop --}}
<div class="restock-table-wrap desktop-restocks">
<table class="data-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Supplier</th>
            <th>Items</th>
            <th>Total cost</th>
            <th>Paid</th>
            <th>Outstanding</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($restocks as $restock)
    @php
        $sb       = $restock->supplierBalance;
        $cost     = $sb ? (float)$sb->total_cost  : 0;
        $paid     = $sb ? (float)$sb->amount_paid : 0;
        $bal      = $sb ? (float)$sb->balance      : 0;
        $settled  = $sb && $sb->settled_at;
        $itemCount = $restock->items->count();
    @endphp
    <tr>
        <td class="td-muted">{{ $restock->created_at->format('d M Y') }}</td>
        <td style="font-weight:500;">{{ $restock->supplier?->name ?? '—' }}</td>
        <td class="td-mono">{{ $itemCount }} {{ $itemCount === 1 ? 'item' : 'items' }}</td>
        <td class="td-mono">{{ $cost > 0 ? tenant('currency_symbol') . ' ' . number_format((int)$cost) : '—' }}</td>
        <td class="td-mono">{{ $paid > 0 ? tenant('currency_symbol') . ' ' . number_format((int)$paid) : '—' }}</td>
        <td class="td-mono {{ $bal > 0 ? 'style=color:var(--clay);font-weight:600' : '' }}">
            @if($bal > 0)
                <span style="color:var(--clay);font-weight:600;">{{ tenant('currency_symbol') }} {{ number_format((int)$bal) }}</span>
            @elseif($cost > 0)
                <span style="color:var(--forest);">—</span>
            @else
                —
            @endif
        </td>
        <td>
            @if(!$sb)
                <span class="badge badge-tan">No cost</span>
            @elseif($settled || $bal <= 0)
                <span class="badge badge-green">Settled</span>
            @else
                <span class="badge badge-clay">Owes {{ tenant('currency_symbol') }} {{ number_format((int)$bal) }}</span>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>

{{-- Mobile --}}
<div class="restock-table-wrap mobile-restocks">
@foreach($restocks as $restock)
@php
    $sb      = $restock->supplierBalance;
    $cost    = $sb ? (float)$sb->total_cost  : 0;
    $paid    = $sb ? (float)$sb->amount_paid : 0;
    $bal     = $sb ? (float)$sb->balance     : 0;
    $settled = $sb && $sb->settled_at;
    $itemCount = $restock->items->count();
@endphp
<div class="restock-card-m">
    <div class="rcm-top">
        <div>
            <p class="rcm-sup">{{ $restock->supplier?->name ?? 'No supplier' }}</p>
            <p class="rcm-date">{{ $restock->created_at->format('d M Y') }}</p>
        </div>
        @if($cost > 0)
            <span class="rcm-cost" style="{{ $bal > 0 ? 'color:var(--clay)' : 'color:var(--forest)' }}">
                {{ tenant('currency_symbol') }} {{ number_format((int)$cost) }}
            </span>
        @endif
    </div>
    <p class="rcm-meta">
        {{ $itemCount }} {{ $itemCount === 1 ? 'item' : 'items' }}
        @if($paid > 0) · Paid {{ tenant('currency_symbol') }} {{ number_format((int)$paid) }} @endif
        @if($bal > 0) · <span style="color:var(--clay);">Owes {{ tenant('currency_symbol') }} {{ number_format((int)$bal) }}</span>
        @elseif($cost > 0) · <span style="color:var(--forest);">Settled</span>
        @endif
    </p>
</div>
@endforeach
</div>

@endif

@endsection
