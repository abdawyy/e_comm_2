@extends('layouts.store', ['title' => __('store.nav.cart'), 'seoKey' => 'cart'])
@section('content')
@php
    $math = app(\App\Services\DecimalMath::class);
    $qtyLabel = function (string $quantity): string {
        $trimmed = rtrim(rtrim($quantity, '0'), '.');

        return $trimmed === '' || $trimmed === '-' ? '0' : $trimmed;
    };
    $addonName = function (mixed $name): string {
        if (is_string($name)) {
            return $name;
        }

        if (! is_array($name)) {
            return '';
        }

        $value = $name[app()->getLocale()] ?? $name['en'] ?? reset($name);

        return is_string($value) ? $value : '';
    };
@endphp
<section class="cart-page">
    <h1 class="cart-title">{{ __('store.nav.cart') }}</h1>
    @if($quote->lines === [])
        <div class="store-card mt-6 p-8 text-center">
            @if($empty = \App\Models\DesignAsset::urlFor('empty_cart'))<img class="empty-art" src="{{ $empty }}" alt="{{ \App\Models\DesignAsset::altFor('empty_cart') }}">@endif
            <p>{{ __('store.cart.empty') }}</p>
            <a class="store-btn-primary mt-4" href="{{ route('shop') }}">{{ __('store.home.shop_cta') }}</a>
        </div>
    @else
        <div class="cart-sheet">
            <div class="cart-head">
                <span>{{ __('store.cart.product') }}</span>
                <span>{{ __('store.cart.price') }}</span>
                <span>{{ __('store.checkout.total') }}</span>
            </div>
            @foreach($quote->lines as $line)
                <article class="cart-row">
                    <div class="cart-product">
                        <div class="cart-thumb">
                            @if($line->url)
                                <a href="{{ $line->url }}">
                                    @if($line->imageUrl)
                                        <img src="{{ $line->imageUrl }}" alt="{{ $line->name }}">
                                    @endif
                                </a>
                            @elseif($line->imageUrl)
                                <img src="{{ $line->imageUrl }}" alt="{{ $line->name }}">
                            @endif
                        </div>
                        <div class="cart-meta">
                            @if($line->url)
                                <a class="cart-name" href="{{ $line->url }}">{{ $line->name }}</a>
                            @else
                                <strong class="cart-name">{{ $line->name }}</strong>
                            @endif
                            @if(filled($line->sku))
                                <p>{{ __('store.cart.item_no') }}: {{ $line->sku }}</p>
                            @endif
                            <p>{{ __('store.product.unit') }}: {{ $line->unit }}</p>
                            @foreach($line->services as $service)
                                @if($serviceName = $addonName($service['name'] ?? null))
                                    <p>{{ $serviceName }}</p>
                                @endif
                            @endforeach
                            @if($line->packaging && ($packagingName = $addonName($line->packaging['name'] ?? null)))
                                <p>{{ $packagingName }}</p>
                            @endif
                            <form class="cart-qty" method="post" action="{{ route('cart.items.update', $line->key) }}">
                                @csrf
                                @method('PATCH')
                                <label for="qty-{{ $line->key }}">{{ __('store.cart.qty') }}:</label>
                                <input id="qty-{{ $line->key }}" class="cart-qty-input" name="quantity" value="{{ $qtyLabel($line->quantity) }}" inputmode="decimal" onchange="this.form.requestSubmit()">
                                <button class="cart-qty-save" type="submit">{{ __('store.cart.update') }}</button>
                            </form>
                            <div class="cart-links">
                                <form method="post" action="{{ route('cart.items.destroy', $line->key) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="cart-link" type="submit">{{ __('store.cart.remove') }}</button>
                                </form>
                                @if($line->url)
                                    <span class="cart-links-bar" aria-hidden="true">|</span>
                                    <a class="cart-link" href="{{ $line->url }}">{{ __('store.cart.edit') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="cart-price">
                        <span class="cart-col-label">{{ __('store.cart.price') }}</span>
                        <span class="cart-money">{{ $math->format($line->unitPrice) }}</span>
                    </div>
                    <div class="cart-total">
                        <span class="cart-col-label">{{ __('store.checkout.total') }}</span>
                        <span class="cart-money">{{ $math->format($line->lineSubtotal) }}</span>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="cart-summary">
            <form class="cart-coupon" method="post" action="{{ route('cart.coupon') }}">
                @csrf
                <label>
                    <span>{{ __('store.cart.coupon_placeholder') }}</span>
                    <input class="store-input" name="code" value="{{ $quote->couponCode }}" placeholder="{{ __('store.cart.coupon_placeholder') }}">
                </label>
                <button class="store-btn-ghost" type="submit">{{ __('store.cart.apply') }}</button>
            </form>
            @if($quote->couponCode)
                <form method="post" action="{{ route('cart.coupon.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button class="cart-link" type="submit">{{ __('store.cart.coupon_remove', ['code' => $quote->couponCode]) }}</button>
                </form>
            @endif
            <dl class="cart-totals">
                <div><dt>{{ __('store.checkout.subtotal') }}</dt><dd>{{ $math->format($quote->subtotal) }}</dd></div>
                <div><dt>{{ __('store.checkout.discount') }}</dt><dd>{{ $math->format($quote->discount) }}</dd></div>
                <div><dt>{{ __('store.checkout.shipping') }}</dt><dd>{{ __('store.cart.shipping_later') }}</dd></div>
                <div class="is-total"><dt>{{ __('store.checkout.total') }}</dt><dd>{{ $math->format($quote->total) }}</dd></div>
            </dl>
            <div class="cart-payments">
                <span class="cart-pay-cash">{{ __('store.payment.cod') }}</span>
                @if(\App\Models\StoreSetting::instapayEnabled())
                    <span class="cart-pay-instapay">
                        @include('store.partials.instapay-logo')
                        <span>{{ \App\Models\StoreSetting::instapayNumber() }}</span>
                    </span>
                @endif
            </div>
            <a class="store-btn-primary cart-checkout" href="{{ route('checkout.create') }}">{{ __('store.cart.checkout') }}</a>
        </div>
    @endif
</section>
@endsection
