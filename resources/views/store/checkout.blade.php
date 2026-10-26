@extends('layouts.store', ['title' => __('store.checkout.title'), 'seoKey' => 'checkout'])
@section('content')
@php($math = app(\App\Services\DecimalMath::class))
@php($goods = $math->maxZero($math->sub($quote->subtotal, $quote->discount)))
<section class="mx-auto grid max-w-6xl gap-8 px-4 py-8 md:grid-cols-[1.2fr_.8fr]">
    <form class="store-card space-y-4 p-6" method="post" action="{{ route('checkout.store') }}" data-checkout-form>
        @csrf
        <input type="hidden" name="nonce" value="{{ $nonce }}">
        <h1 class="text-2xl font-bold">{{ __('store.checkout.title') }}</h1>
        <input class="store-input" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" placeholder="{{ __('store.checkout.name') }}" required>
        <input class="store-input" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" placeholder="{{ __('store.checkout.email') }}">
        <input class="store-input" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="{{ __('store.checkout.phone') }}" required>
        <select class="store-input" name="city_id" required>
            <option value="" data-fee="0.0000">{{ __('store.checkout.city') }}</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" data-fee="{{ $math->normalize((string) $city->shipping_fee) }}" @selected(old('city_id') == $city->id)>{{ $city->name }} — {{ $math->format((string) $city->shipping_fee) }}</option>
            @endforeach
        </select>
        <p class="text-sm opacity-80">{{ __('store.checkout.shipping') }}: <strong data-shipping>{{ $math->format('0') }}</strong></p>
        <textarea class="store-input" name="address" required placeholder="{{ __('store.checkout.address') }}">{{ old('address', auth()->user()->address ?? '') }}</textarea>
        @if($orderPackaging->isNotEmpty())
            <select class="store-input" name="order_packaging_id">
                <option value="" data-fee="0.0000">{{ __('store.checkout.order_packaging') }}</option>
                @foreach($orderPackaging as $packaging)
                    <option value="{{ $packaging->id }}" data-fee="{{ $math->surcharge($quote->subtotal, $packaging->surcharge_type, (string) $packaging->surcharge_value) }}">{{ $packaging->name }}</option>
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
    <aside class="store-card h-fit space-y-3 p-6" data-summary data-goods="{{ $goods }}" data-currency="{{ config('store.currency', 'EGP') }}">
        @foreach($quote->lines as $line)
            <p class="flex items-center justify-between gap-3 text-sm">
                <span class="flex min-w-0 items-center gap-3">
                    @if($line->imageUrl)
                        <img class="h-14 w-14 shrink-0 rounded-lg object-cover" src="{{ $line->imageUrl }}" alt="{{ $line->name }}">
                    @endif
                    <span class="min-w-0">{{ $line->name }}</span>
                </span>
                <span>{{ $math->format($line->lineSubtotal) }}</span>
            </p>
        @endforeach
        <p class="flex justify-between text-sm"><span>{{ __('store.checkout.subtotal') }}</span><span>{{ $math->format($quote->subtotal) }}</span></p>
        <p class="flex justify-between text-sm"><span>{{ __('store.checkout.discount') }}</span><span>{{ $math->format($quote->discount) }}</span></p>
        <p class="flex justify-between text-sm"><span>{{ __('store.checkout.shipping') }}</span><span data-shipping>{{ $math->format('0') }}</span></p>
        <p class="flex justify-between font-bold"><span>{{ __('store.checkout.total') }}</span><span data-total>{{ $math->format($goods) }}</span></p>
    </aside>
</section>
<script>
    (() => {
        const form = document.querySelector('[data-checkout-form]');
        const summary = document.querySelector('[data-summary]');
        if (!form || !summary) return;

        const toMinor = (value) => {
            const raw = String(value || '0').trim();
            const negative = raw.startsWith('-');
            const [whole, fraction = ''] = raw.replace('-', '').split('.');
            const minor = (parseInt(whole || '0', 10) * 10000) + parseInt((fraction + '0000').slice(0, 4), 10);
            return negative ? -minor : minor;
        };

        const format = (minor) => {
            const negative = minor < 0;
            const cents = Math.round(Math.abs(minor) / 100);
            const whole = Math.floor(cents / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            const fraction = String(cents % 100).padStart(2, '0');
            return (negative ? '-' : '') + whole + '.' + fraction + ' ' + summary.dataset.currency;
        };

        const feeOf = (select) => {
            if (!select || select.value === '') return 0;
            return toMinor(select.selectedOptions[0]?.dataset.fee || '0');
        };

        const paint = () => {
            const shipping = feeOf(form.querySelector('[name="city_id"]'));
            const packaging = feeOf(form.querySelector('[name="order_packaging_id"]'));
            const total = toMinor(summary.dataset.goods) + shipping + packaging;
            document.querySelectorAll('[data-shipping]').forEach((node) => { node.textContent = format(shipping); });
            const totalNode = summary.querySelector('[data-total]');
            if (totalNode) totalNode.textContent = format(total);
        };

        form.addEventListener('change', (event) => {
            if (event.target.name === 'city_id' || event.target.name === 'order_packaging_id') paint();
        });
        paint();
    })();
</script>
@endsection
