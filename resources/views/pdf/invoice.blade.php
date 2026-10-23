<!DOCTYPE html>
<html lang="{{ $order->locale }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; color: #111; }
        h1 { color: {{ $theme->css('primary') }}; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border-bottom: 1px solid #ddd; padding: 6px; text-align: start; }
        .brand { font-size: 22px; font-weight: bold; }
    </style>
</head>
<body>
    @if($logo)<img src="{{ $logo }}" style="height:42px" alt="{{ $theme->brand_name }}">@endif
    <div class="brand">{{ $theme->brand_name }}</div>
    <h1>{{ __('store.invoice.title') }} {{ $order->reference }}</h1>
    <p>{{ $order->customer_name }} · {{ $order->customer_phone }}</p>
    <p>{{ $order->city_name }} — {{ $order->address }}</p>
    <table>
        <thead><tr><th>{{ __('store.invoice.item') }}</th><th>{{ __('store.invoice.qty') }}</th><th>{{ __('store.invoice.total') }}</th></tr></thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->localizedName() }}</td>
                <td>{{ $item->quantity }} {{ $item->unit }}</td>
                <td>{{ $math->format((string) $item->line_subtotal) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p>{{ __('store.checkout.subtotal') }}: {{ $math->format((string) $order->subtotal) }}</p>
    <p>{{ __('store.checkout.discount') }}: {{ $math->format((string) $order->discount) }}</p>
    <p>{{ __('store.checkout.shipping') }}: {{ $math->format((string) $order->shipping_fee) }}</p>
    <p><strong>{{ __('store.checkout.total') }}: {{ $math->format((string) $order->total) }}</strong></p>
</body>
</html>
