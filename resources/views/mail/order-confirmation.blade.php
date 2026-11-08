<div style="font-family: Arial, Helvetica, sans-serif; color: #1A2B28; max-width: 560px;">
    <div style="background: {{ $theme->css('primary') }}; color: #ffffff; padding: 16px 20px;">
        @if($logo)<img src="{{ $logo }}" alt="{{ $theme->brand_name }}" style="height:36px; display:block; margin-bottom:8px;">@endif
        <strong>{{ $theme->brand_name }}</strong>
    </div>
    <div style="padding: 20px; background: #ffffff;">
        <p>{{ __('store.mail.confirmed_heading', ['reference' => $order->reference], $locale) }}</p>
        <p>{{ $order->customer_name }}</p>
        <p>{{ __('store.mail.confirmed_body', [], $locale) }}</p>
        @foreach($order->items as $item)
            <p>{{ $item->localizedName($locale) }} · {{ $item->quantity }} {{ $item->unit }} · {{ $math->format((string) $item->line_subtotal) }}</p>
        @endforeach
        <p><strong>{{ __('store.checkout.total', [], $locale) }}: {{ $math->format((string) $order->total) }}</strong></p>
        <p>{{ __('store.mail.pdf_attached', [], $locale) }}</p>
    </div>
</div>
