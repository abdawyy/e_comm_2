<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; color: #1A2B28; }
        h1 { color: {{ $theme->css('primary') }}; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border-bottom: 1px solid #C9A55C; padding: 6px; text-align: start; }
        .brand { font-size: 22px; font-weight: bold; }
        .section { margin-top: 28px; }
        .ar { direction: rtl; text-align: right; }
    </style>
</head>
<body>
    @if($logo)<img src="{{ $logo }}" style="height:42px" alt="{{ $theme->brand_name }}">@endif
    <div class="brand">{{ $theme->brand_name }}</div>
    @foreach(['en' => 'ltr', 'ar' => 'rtl'] as $locale => $dir)
        <div class="section" dir="{{ $dir }}" @if($locale === 'ar') style="direction: rtl; text-align: right;" @endif>
            <h1>{{ __('store.invoice.title', [], $locale) }} {{ $order->reference }}</h1>
            <p>{{ $order->customer_name }} · {{ $order->customer_phone }} · {{ $order->customer_email }}</p>
            <p>{{ $order->city_name }} — {{ $order->address }}</p>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('store.invoice.item', [], $locale) }}</th>
                        <th>{{ __('store.invoice.qty', [], $locale) }}</th>
                        <th>{{ __('store.invoice.total', [], $locale) }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->localizedName($locale) }}</td>
                        <td>{{ $item->quantity }} {{ $item->unit }}</td>
                        <td>{{ $math->format((string) $item->line_subtotal) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <p>{{ __('store.checkout.subtotal', [], $locale) }}: {{ $math->format((string) $order->subtotal) }}</p>
            <p>{{ __('store.checkout.discount', [], $locale) }}: {{ $math->format((string) $order->discount) }}</p>
            <p>{{ __('store.checkout.shipping', [], $locale) }}: {{ $math->format((string) $order->shipping_fee) }}</p>
            <p><strong>{{ __('store.checkout.total', [], $locale) }}: {{ $math->format((string) $order->total) }}</strong></p>
        </div>
    @endforeach
</body>
</html>
