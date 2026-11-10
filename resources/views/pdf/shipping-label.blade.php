<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; color: #1A2B28; font-size: 12px; }
        h1 { font-size: 16px; margin: 0 0 8px; }
        .box { border: 2px solid #1A2B28; padding: 10px; }
        p { margin: 4px 0; }
    </style>
</head>
<body @if($locale === 'ar') dir="rtl" @endif>
    <div class="box">
        <h1>{{ __('store.delivery.label', [], $locale) }}</h1>
        <p><strong>{{ __('admin.fields.reference', [], $locale) }}:</strong> {{ $order->reference }}</p>
        <p><strong>{{ __('store.delivery.carrier', [], $locale) }}:</strong> {{ $order->shippingCarrier?->getTranslation('name', $locale) }}</p>
        <p><strong>{{ __('admin.fields.customer', [], $locale) }}:</strong> {{ $order->customer_name }}</p>
        <p><strong>{{ __('admin.fields.phone', [], $locale) }}:</strong> {{ $order->customer_phone }}</p>
        <p><strong>{{ __('admin.fields.city', [], $locale) }}:</strong> {{ $order->city_name }}</p>
        <p><strong>{{ __('admin.fields.address', [], $locale) }}:</strong> {{ $order->address }}</p>
        @if($order->shipping_label_fee !== null)
            <p><strong>{{ __('store.delivery.fee', [], $locale) }}:</strong> {{ $math->format((string) $order->shipping_label_fee) }}</p>
        @endif
    </div>
</body>
</html>
