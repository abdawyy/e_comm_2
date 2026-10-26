<div style="font-family: sans-serif; color: #111;">
    <div style="background: {{ $theme->css('primary') }}; color: white; padding: 16px;">
        @if($logo)<img src="{{ $logo }}" alt="{{ $theme->brand_name }}" style="height:36px">@endif
        <strong>{{ $theme->brand_name }}</strong>
    </div>
    <div style="padding: 16px;">
        <h1>{{ __('store.mail.confirmed_heading', ['reference' => $order->reference], $order->locale) }}</h1>
        <p>{{ __('store.mail.confirmed_body', [], $order->locale) }}</p>
        <p>{{ __('store.mail.pdf_attached', [], $order->locale) }}</p>
        <p><a href="{{ $invoiceUrl }}">{{ __('store.orders.invoice', [], $order->locale) }}</a></p>
    </div>
</div>
