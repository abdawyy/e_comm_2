<div style="font-family: Arial, Helvetica, sans-serif; color: #1A2B28;">
    <div style="background: {{ $theme->css('primary') }}; color: #ffffff; padding: 16px 20px;">
        <strong>{{ $theme->brand_name }}</strong>
    </div>
    <div style="padding: 20px;">
        <p>{{ __('store.mail.payment_body', [
            'reference' => $order->reference,
            'from' => __('admin.payment.'.$fromStatus, [], $locale),
            'to' => __('admin.payment.'.$toStatus, [], $locale),
        ], $locale) }}</p>
        <p>{{ $order->customer_name }}</p>
    </div>
</div>
