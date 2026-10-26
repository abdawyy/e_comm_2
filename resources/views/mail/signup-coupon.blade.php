<div style="font-family: sans-serif; color: #1A2B28;">
    <div style="background: {{ $theme->css('primary') }}; color: {{ $theme->css('surface') }}; padding: 16px;">
        <strong>{{ $theme->brand_name }}</strong>
    </div>
    <div style="padding: 16px;">
        <h1>{{ __('store.signup.mail_heading', [], $locale) }}</h1>
        <p>{{ __('store.signup.mail_body', [], $locale) }}</p>
        <p style="font-size: 28px; letter-spacing: 0.08em;"><strong>{{ $coupon->code }}</strong></p>
        <p>{{ $coupon->type === 'percent' ? rtrim(rtrim((string) $coupon->value, '0'), '.').'%' : $coupon->value }}</p>
        <hr>
        <div dir="rtl" style="text-align: right;">
            <h1>{{ __('store.signup.mail_heading', [], 'ar') }}</h1>
            <p>{{ __('store.signup.mail_body', [], 'ar') }}</p>
            <p style="font-size: 28px;"><strong>{{ $coupon->code }}</strong></p>
        </div>
    </div>
</div>
