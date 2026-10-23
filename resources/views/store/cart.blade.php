@extends('layouts.store', ['title' => __('store.nav.cart'), 'seoKey' => 'cart'])
@section('content')
<section class="mx-auto max-w-4xl px-4 py-8">
    <h1 class="text-3xl font-bold">{{ __('store.nav.cart') }}</h1>
    @if($quote->lines === [])
        <div class="store-card mt-6 p-8 text-center">
            @if($empty = \App\Models\DesignAsset::urlFor('empty_cart'))<img class="mx-auto mb-4 h-40" src="{{ $empty }}" alt="{{ \App\Models\DesignAsset::altFor('empty_cart') }}">@endif
            <p>{{ __('store.cart.empty') }}</p>
            <a class="store-btn-primary mt-4" href="{{ route('shop') }}">{{ __('store.home.shop_cta') }}</a>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach($quote->lines as $line)
                <article class="store-card flex items-center justify-between gap-4 p-4">
                    <div>
                        <strong>{{ $line->name }}</strong>
                        <p class="text-sm opacity-70">{{ $line->quantity }} {{ $line->unit }}</p>
                    </div>
                    <div class="text-end">
                        <p>{{ app(\App\Services\DecimalMath::class)->format($line->lineSubtotal) }}</p>
                        <form method="post" action="{{ route('cart.items.destroy', $line->key) }}">@csrf @method('DELETE')<button class="text-sm" type="submit">{{ __('store.cart.remove') }}</button></form>
                    </div>
                </article>
            @endforeach
        </div>
        <form class="mt-4 flex gap-2" method="post" action="{{ route('cart.coupon') }}">
            @csrf
            <input class="store-input" name="code" placeholder="{{ __('store.cart.coupon_placeholder') }}">
            <button class="store-btn-ghost" type="submit">{{ __('store.cart.apply') }}</button>
        </form>
        <dl class="mt-6 space-y-1">
            <div class="flex justify-between"><dt>{{ __('store.checkout.subtotal') }}</dt><dd>{{ app(\App\Services\DecimalMath::class)->format($quote->subtotal) }}</dd></div>
            <div class="flex justify-between"><dt>{{ __('store.checkout.discount') }}</dt><dd>{{ app(\App\Services\DecimalMath::class)->format($quote->discount) }}</dd></div>
            <div class="flex justify-between font-bold"><dt>{{ __('store.checkout.total') }}</dt><dd>{{ app(\App\Services\DecimalMath::class)->format($quote->total) }}</dd></div>
        </dl>
        <a class="store-btn-primary mt-6" href="{{ route('checkout.create') }}">{{ __('store.cart.checkout') }}</a>
    @endif
</section>
@endsection
