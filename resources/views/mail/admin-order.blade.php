<div style="font-family: sans-serif;">
    <div style="background: {{ $theme->css('primary') }}; color: white; padding: 16px;">
        @if($logo)<img src="{{ $logo }}" alt="" style="height:36px">@endif
        <strong>{{ $theme->brand_name }}</strong>
    </div>
    <div style="padding: 16px;">
        <p>{{ __('store.mail.admin_body', ['reference' => $order->reference, 'total' => $order->total]) }}</p>
    </div>
</div>
