@extends('layouts.store', ['title' => __('store.nav.cart'), 'seoKey' => 'cart'])
@section('content')
<section class="mx-auto max-w-4xl px-4 py-8">
    <h1 class="text-3xl font-bold">{{ __('store.nav.cart') }}</h1>
    @if($quote->lines === [])
        <div class="store-card mt-6 p-8 text-center">
            @if($empty = \App\Models\DesignAsset::urlFor('empty_cart'))<img class="empty-art" src="{{ $empty }}" alt="{{ \App\Models\DesignAsset::altFor('empty_cart') }}">@endif
            <p>{{ __('store.cart.empty') }}</p>
            <a class="store-btn-primary mt-4" href="{{ route('shop') }}">{{ __('store.home.shop_cta') }}</a>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach($quote->lines as $line)
                <article class="store-card cart-line">
                    <div class="cart-line-main">
                        @if($line->imageUrl)
                            <img src="{{ $line->imageUrl }}" alt="{{ $line->name }}">
                        @endif
                        <div>
                            <strong>{{ $line->name }}</strong>
                            <p>{{ app(\App\Services\DecimalMath::class)->format($line->lineSubtotal) }}</p>
                        </div>
                    </div>
                    <form class="cart-actions" method="post" action="{{ route('cart.items.update', $line->key) }}">
                        @csrf
                        @method('PATCH')
                        <label for="qty-{{ $line->key }}">{{ __('store.product.quantity') }}</label>
                        <input id="qty-{{ $line->key }}" class="store-input" name="quantity" value="{{ rtrim(rtrim((string) $line->quantity, '0'), '.') }}" inputmode="decimal">
                        <span>{{ $line->unit }}</span>
                        <button class="store-btn-ghost" type="submit">{{ __('store.cart.update') }}</button>
                    </form>
                    <form method="post" action="{{ route('cart.items.destroy', $line->key) }}">@csrf @method('DELETE')<button class="cart-remove" type="submit">{{ __('store.cart.remove') }}</button></form>
                </article>
            @endforeach
        </div>
        <form class="cart-coupon" method="post" action="{{ route('cart.coupon') }}">
            @csrf
            <label>
                <span>{{ __('store.cart.coupon_placeholder') }}</span>
                <input class="store-input" name="code" value="{{ $quote->couponCode }}" placeholder="{{ __('store.cart.coupon_placeholder') }}">
            </label>
            <button class="store-btn-ghost" type="submit">{{ __('store.cart.apply') }}</button>
        </form>
        @if($quote->couponCode)
            <form class="mt-2" method="post" action="{{ route('cart.coupon.destroy') }}">
                @csrf
                @method('DELETE')
                <button class="text-sm" type="submit">{{ __('store.cart.coupon_remove', ['code' => $quote->couponCode]) }}</button>
            </form>
        @endif
        <dl class="mt-6 space-y-1">
            <div class="flex justify-between"><dt>{{ __('store.checkout.subtotal') }}</dt><dd>{{ app(\App\Services\DecimalMath::class)->format($quote->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt>{{ __('store.checkout.discount') }}</dt><dd>{{ app(\App\Services\DecimalMath::class)->format($quote->discount) }}</dd></div>
            <div class="flex justify-between"><dt>{{ __('store.checkout.shipping') }}</dt><dd>{{ __('store.cart.shipping_later') }}</dd></div>
            <div class="flex justify-between font-bold"><dt>{{ __('store.checkout.total') }}</dt><dd>{{ app(\App\Services\DecimalMath::class)->format($quote->total) }}</dd></div>
        </dl>
        <a class="store-btn-primary cart-checkout" href="{{ route('checkout.create') }}">{{ __('store.cart.checkout') }}</a>
    @endif
</section>
@endsection
