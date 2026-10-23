@extends('layouts.store', ['title' => __('store.track.title'), 'seoKey' => 'track'])
@section('content')
<section class="mx-auto max-w-3xl px-4 py-10">
    <h1 class="text-3xl font-bold">{{ __('store.track.title') }}</h1>
    <p class="mt-2 opacity-80">{{ __('store.track.intro') }}</p>
    <form class="store-card mt-6 grid gap-3 p-5 md:grid-cols-[1fr_1fr_auto]" method="get" action="{{ route('orders.track') }}">
        <input class="store-input" name="reference" value="{{ request('reference') }}" placeholder="{{ __('store.track.reference') }}" required>
        <input class="store-input" name="phone" value="{{ request('phone') }}" placeholder="{{ __('store.checkout.phone') }}">
        <button class="store-btn-primary" type="submit">{{ __('store.track.lookup') }}</button>
    </form>
    @if($miss)
        <p class="mt-4" style="color: var(--color-danger)">{{ __('store.track.missing') }}</p>
    @endif
    @if($order)
        <article class="store-card mt-6 p-5">
            <p class="store-kicker">{{ $order->reference }}</p>
            <h2 class="mt-2 text-2xl font-bold">{{ __('store.track.status', ['status' => __('store.track.steps.'.$order->status)]) }}</h2>
            <p class="mt-1">{{ __('store.thanks.payment', ['status' => $order->payment_status]) }}</p>
            @include('store.partials.order-timeline', ['order' => $order])
            <ul class="space-y-2 text-sm">
                @foreach($order->items as $item)
                    <li class="flex justify-between"><span>{{ $item->localizedName() }} × {{ $item->quantity }}</span><span>{{ app(\App\Services\DecimalMath::class)->format((string) $item->line_subtotal) }}</span></li>
                @endforeach
            </ul>
            <p class="mt-4 font-bold">{{ __('store.checkout.total') }} {{ app(\App\Services\DecimalMath::class)->format((string) $order->total) }}</p>
            <p class="mt-2 text-sm opacity-80">{{ $order->city_name }} — {{ $order->address }}</p>
        </article>
    @endif
</section>
@endsection
