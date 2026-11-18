<p>{{ $theme->brand_name }}</p>
<p>{{ __('store.inquiry.admin_intro') }}</p>
<p><strong>{{ __('store.inquiry.name') }}:</strong> {{ $inquiry->customer_name }}</p>
<p><strong>{{ __('store.inquiry.phone') }}:</strong> {{ $inquiry->customer_phone }}</p>
@if($inquiry->customer_email)
    <p><strong>{{ __('store.checkout.email') }}:</strong> {{ $inquiry->customer_email }}</p>
@endif
<p><strong>{{ __('store.inquiry.product') }}:</strong> {{ $inquiry->product?->name }}</p>
@if($inquiry->message)
    <p>{{ $inquiry->message }}</p>
@endif
