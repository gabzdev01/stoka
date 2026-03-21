<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt — {{ shop_name() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            color: #1C1814;
            padding: 24px 20px;
            max-width: 320px;
            margin: 0 auto;
        }

        .r-shop { font-size: 18px; font-weight: 700; text-align: center; margin-bottom: 2px; }
        .r-sub  { font-size: 11px; color: #8C7B6E; text-align: center; margin-bottom: 16px; }
        .r-divider { border: none; border-top: 1px dashed #D9CEBC; margin: 12px 0; }

        .r-meta { font-size: 11px; color: #8C7B6E; margin-bottom: 2px; }

        .r-items { width: 100%; border-collapse: collapse; margin: 8px 0; }
        .r-items td { padding: 5px 0; vertical-align: top; }
        .r-items .td-name { width: 65%; }
        .r-items .td-amt  { width: 35%; text-align: right; font-family: 'DM Mono', monospace; }
        .r-item-name { font-weight: 500; }
        .r-item-sub  { font-size: 11px; color: #8C7B6E; }

        .r-total-row { display: flex; justify-content: space-between; align-items: baseline; margin-top: 8px; }
        .r-total-label { font-weight: 600; font-size: 14px; }
        .r-total-amt   { font-family: 'DM Mono', monospace; font-size: 18px; font-weight: 500; }

        .r-payment { font-size: 12px; color: #5C3D2E; font-weight: 600; margin-top: 6px; }

        .r-footer { font-size: 11px; color: #8C7B6E; text-align: center; margin-top: 16px; line-height: 1.5; }
        .r-powered { font-size: 10px; color: #C4B9A8; text-align: center; margin-top: 8px; }

        @media print {
            html, body { width: 80mm; }
            body { padding: 8px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="r-shop">{{ shop_name() }}</div>
<div class="r-sub">
    @if(tenant('shop_location')){{ tenant('shop_location') }} · @endif
    {{ now()->format('D j M Y · H:i') }}
</div>

<hr class="r-divider">

@php
    $firstSale = $sales->first();
    $staffName = $firstSale?->staff?->name ?? '';
    $staffFirst = $staffName ? explode(' ', trim($staffName))[0] : 'Staff';
    $paymentType = $firstSale?->payment_type ?? 'cash';
    $paymentLabel = match($paymentType) {
        'cash'  => 'Cash',
        'mpesa' => 'M-Pesa',
        'credit'=> 'Credit',
        default => ucfirst($paymentType),
    };
    $grandTotal = $sales->sum('total');
    $cs = tenant('currency_symbol');
@endphp

<div class="r-meta">Staff: {{ $staffFirst }}</div>

<hr class="r-divider">

<table class="r-items">
@foreach($sales as $sale)
    @php
        $qty = $sale->product?->type === 'measured'
            ? number_format($sale->quantity_or_ml, 0) . 'ml'
            : (((int)$sale->quantity_or_ml !== 1) ? '×' . (int)$sale->quantity_or_ml : '');
        $variantLabel = $sale->variant ? ($sale->variant->size . ($sale->variant->colour ? ' / ' . $sale->variant->colour : '')) : null;
    @endphp
    <tr>
        <td class="td-name">
            <div class="r-item-name">{{ $sale->product?->name ?? 'Item' }}</div>
            @if($variantLabel || $qty)
                <div class="r-item-sub">{{ implode(' · ', array_filter([$variantLabel, $qty])) }}</div>
            @endif
        </td>
        <td class="td-amt">{{ $cs }} {{ number_format((int)$sale->total) }}</td>
    </tr>
@endforeach
</table>

<hr class="r-divider">

<div class="r-total-row">
    <span class="r-total-label">Total</span>
    <span class="r-total-amt">{{ $cs }} {{ number_format((int)$grandTotal) }}</span>
</div>
<div class="r-payment">Paid by {{ $paymentLabel }} ✓</div>

@if(tenant('receipt_footer'))
<hr class="r-divider">
<div class="r-footer">{{ tenant('receipt_footer') }}</div>
@endif

<div class="r-powered">Powered by Stoka · stoka.co.ke</div>

<script>
    window.onload = function () {
        window.print();
    };
    window.onafterprint = function () {
        window.close();
    };
</script>
</body>
</html>
