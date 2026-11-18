<form class="product-buy" method="post" action="{{ route('products.inquiries.store', $product) }}">
    @csrf
    <p class="product-lead">{{ __('store.inquiry.help') }}</p>
    <label class="block">
        <span class="product-label">{{ __('store.inquiry.name') }}</span>
        <input class="store-input" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required maxlength="120">
    </label>
    <label class="block">
        <span class="product-label">{{ __('store.inquiry.phone') }}</span>
        <input class="store-input" name="customer_phone" inputmode="tel" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required maxlength="32">
    </label>
    <label class="block">
        <span class="product-label">{{ __('store.checkout.email') }} <small>{{ __('store.checkout.optional') }}</small></span>
        <input class="store-input" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" maxlength="190">
    </label>
    <label class="block">
        <span class="product-label">{{ __('store.inquiry.message') }} <small>{{ __('store.checkout.optional') }}</small></span>
        <textarea class="store-input" name="message" rows="3" maxlength="1000">{{ old('message') }}</textarea>
    </label>
    <button class="store-btn-primary product-add" type="submit">{{ __('store.inquiry.request') }}</button>
</form>
