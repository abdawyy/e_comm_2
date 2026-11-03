@extends('layouts.store', ['title' => __('store.thanks.title')])
@section('content')
<section class="mx-auto max-w-xl px-4 py-16 text-center">
    <h1 class="text-3xl font-bold">{{ __('store.thanks.title') }}</h1>
    @if($order)
        <p class="mt-3">{{ __('store.thanks.reference', ['reference' => $order->reference]) }}</p>
        <p class="mt-2">{{ __('store.thanks.payment', ['status' => __('store.payment_states.'.$order->payment_status)]) }}</p>
        <a class="store-btn-primary mt-4" href="{{ route('orders.track', ['reference' => $order->reference]) }}">{{ __('store.track.title') }}</a>
        @auth
            <a class="store-btn-accent mt-4" href="{{ url('/my/orders') }}">{{ __('store.orders.plural') }}</a>
        @else
            <a class="store-btn-accent mt-4" href="{{ route('login') }}">{{ __('store.nav.account') }}</a>
        @endauth
        @if(session('payment_error'))<p class="mt-3" style="color: var(--color-danger)">{{ session('payment_error') }}</p>@endif
        @if($order->payment_gateway === 'paymob_card' && in_array($order->payment_status, ['pending', 'failed']))
            <form class="mt-4" method="post" action="{{ route('checkout.paymob.retry', $order) }}">@csrf<button class="store-btn-accent" type="submit">{{ __('store.payment.retry') }}</button></form>
        @endif
    @else
        <p class="mt-3">{{ __('store.thanks.missing') }}</p>
        <a class="store-btn-primary mt-4" href="{{ route('shop') }}">{{ __('store.home.shop_cta') }}</a>
    @endif
</section>
@endsection
