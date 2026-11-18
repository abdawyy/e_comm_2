<p>{{ $theme->brand_name }}</p>
<p>{{ __('store.inquiry.reply_intro', ['name' => $inquiry->customer_name]) }}</p>
<p><strong>{{ __('store.inquiry.product') }}:</strong> {{ $inquiry->product?->name }}</p>
<p>{{ $inquiry->admin_reply }}</p>
