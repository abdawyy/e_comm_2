@extends('layouts.store', ['title' => __('store.payment.return_title')])
@section('content')
<section class="mx-auto max-w-xl px-4 py-16 text-center" x-data="{ status: @js($order?->payment_status), tries: 0 }" x-init="
    const poll = async () => {
        if (! @js($order?->reference) || tries > 5 || status === 'paid' || status === 'failed') return;
        tries++;
        const response = await fetch(@js(route('checkout.paymob.status', ['reference' => $order?->reference])));
        const data = await response.json();
        status = data.payment_status;
        if (status !== 'paid' && status !== 'failed') setTimeout(poll, 1000);
    };
    poll();
">
    <h1 class="text-3xl font-bold" x-show="status === 'paid'">{{ __('store.payment.received') }}</h1>
    <h1 class="text-3xl font-bold" x-show="status === 'failed'">{{ __('store.payment.failed') }}</h1>
    <h1 class="text-3xl font-bold" x-show="status !== 'paid' && status !== 'failed'">{{ __('store.payment.confirming') }}</h1>
    <p class="mt-3">{{ $order?->reference }}</p>
    <a class="store-btn-primary mt-6" href="{{ route('checkout.thanks', ['reference' => $order?->reference]) }}">{{ __('store.thanks.title') }}</a>
</section>
@endsection
