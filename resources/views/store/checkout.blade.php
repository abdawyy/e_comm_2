@extends('layouts.store', ['title' => __('store.checkout.title'), 'seoKey' => 'checkout'])
@section('content')
<section class="mx-auto grid max-w-6xl gap-8 px-4 py-8 md:grid-cols-[1.2fr_.8fr]">
    <form class="store-card space-y-4 p-6" method="post" action="{{ route('checkout.store') }}">
        @csrf
        <input type="hidden" name="nonce" value="{{ $nonce }}">
        <h1 class="text-2xl font-bold">{{ __('store.checkout.title') }}</h1>
        <input class="store-input" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" placeholder="{{ __('store.checkout.name') }}" required>
        <input class="store-input" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" placeholder="{{ __('store.checkout.email') }}">
        <input class="store-input" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="{{ __('store.checkout.phone') }}" required>
        <select class="store-input" name="city_id" required>
            <option value="">{{ __('store.checkout.city') }}</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }} — {{ app(\App\Services\DecimalMath::class)->format((string) $city->shipping_fee) }}</option>
            @endforeach
        </select>
        <textarea class="store-input" name="address" required placeholder="{{ __('store.checkout.address') }}">{{ old('address', auth()->user()->address ?? '') }}</textarea>
        @if($orderPackaging->isNotEmpty())
            <select class="store-input" name="order_packaging_id">
                <option value="">{{ __('store.checkout.order_packaging') }}</option>
                @foreach($orderPackaging as $packaging)
                    <option value="{{ $packaging->id }}">{{ $packaging->name }}</option>
                @endforeach
            </select>
        @endif
        <fieldset class="space-y-2">
            <legend class="font-semibold">{{ __('store.checkout.payment') }}</legend>
            @foreach($gateways as $gateway)
                <label class="flex items-center gap-2">
                    <input type="radio" name="payment_gateway" value="{{ $gateway->id() }}" @checked($loop->first) required>
                    {{ $gateway->label() }}
                </label>
            @endforeach
        </fieldset>
        <button class="store-btn-primary" type="submit">{{ __('store.checkout.place') }}</button>
    </form>
    <aside class="store-card h-fit p-6">
        @foreach($quote->lines as $line)
            <p class="flex justify-between text-sm"><span>{{ $line->name }}</span><span>{{ app(\App\Services\DecimalMath::class)->format($line->lineSubtotal) }}</span></p>
        @endforeach
        <p class="mt-4 flex justify-between font-bold"><span>{{ __('store.checkout.total') }}</span><span>{{ app(\App\Services\DecimalMath::class)->format($quote->total) }}</span></p>
    </aside>
</section>
@endsection
