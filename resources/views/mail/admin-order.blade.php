<div style="font-family: sans-serif;">
    <div style="background: {{ $theme->css('primary') }}; color: white; padding: 16px;">
        @if($logo)<img src="{{ $logo }}" alt="" style="height:36px">@endif
        <strong>{{ $theme->brand_name }}</strong>
    </div>
    <div style="padding: 16px;">
        <p><strong>New order {{ $order->reference }}</strong></p>
        <p>طلب جديد {{ $order->reference }}</p>
        <p>{{ $order->customer_name }} · {{ $order->customer_email }} · {{ $order->customer_phone }}</p>
        <p>{{ $order->city_name }} — {{ $order->address }}</p>
        <p>{{ $order->payment_gateway }} · {{ $order->payment_status }} · {{ $order->total }} {{ $order->currency }}</p>
    </div>
</div>
