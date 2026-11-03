@extends('layouts.store', ['title' => __('store.checkout.title'), 'seoKey' => 'checkout'])
@section('content')
@php($math = app(\App\Services\DecimalMath::class))
@php($goods = $math->maxZero($math->sub($quote->subtotal, $quote->discount)))
<section class="checkout-layout">
    <form class="store-card checkout-form" method="post" action="{{ route('checkout.store') }}" data-checkout-form>
        @csrf
        <input type="hidden" name="nonce" value="{{ $nonce }}">
        <h1>{{ __('store.checkout.title') }}</h1>
        <label class="block"><span>{{ __('store.checkout.name') }}</span><input class="store-input" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required></label>
        <label class="block"><span>{{ __('store.checkout.email') }}</span><input class="store-input" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}"></label>
        <label class="block"><span>{{ __('store.checkout.phone') }}</span><input class="store-input" name="customer_phone" inputmode="tel" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required></label>
        <label class="block"><span>{{ __('store.checkout.city') }}</span>
            <select class="store-input" name="city_id" required>
                <option value="" data-fee="0.0000">{{ __('store.checkout.city') }}</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" data-fee="{{ $math->normalize((string) $city->shipping_fee) }}" @selected(old('city_id') == $city->id)>{{ $city->name }} — {{ $math->format((string) $city->shipping_fee) }}</option>
                @endforeach
            </select>
        </label>
        <p class="checkout-note">{{ __('store.checkout.shipping') }}: <strong data-shipping>{{ $math->format('0') }}</strong></p>
        <label class="block"><span>{{ __('store.checkout.address') }}</span><textarea class="store-input" name="address" rows="3" required>{{ old('address', auth()->user()->address ?? '') }}</textarea></label>
        @if($orderPackaging->isNotEmpty())
            <label class="block"><span>{{ __('store.checkout.order_packaging') }}</span>
                <select class="store-input" name="order_packaging_id">
                    <option value="" data-fee="0.0000">{{ __('store.checkout.order_packaging') }}</option>
                    @foreach($orderPackaging as $packaging)
                        <option value="{{ $packaging->id }}" data-fee="{{ $math->surcharge($quote->subtotal, $packaging->surcharge_type, (string) $packaging->surcharge_value) }}">{{ $packaging->name }}</option>
                    @endforeach
                </select>
            </label>
        @endif
        <fieldset>
            <legend>{{ __('store.checkout.payment') }}</legend>
            @foreach($gateways as $gateway)
                <label class="checkout-pay">
                    <input type="radio" name="payment_gateway" value="{{ $gateway->id() }}" @checked($loop->first) required>
                    <span>{{ $gateway->label() }}</span>
                </label>
            @endforeach
        </fieldset>
        <button class="store-btn-primary checkout-submit" type="submit">{{ __('store.checkout.place') }}</button>
    </form>
    <aside class="store-card checkout-summary" data-summary data-goods="{{ $goods }}" data-currency="{{ config('store.currency', 'EGP') }}">
        <h2>{{ __('store.checkout.total') }}</h2>
        @foreach($quote->lines as $line)
            <div class="checkout-line">
                @if($line->imageUrl)
                    <img src="{{ $line->imageUrl }}" alt="{{ $line->name }}">
                @else
                    <span class="checkout-thumb" aria-hidden="true"></span>
                @endif
                <p>{{ $line->name }}</p>
                <strong>{{ $math->format($line->lineSubtotal) }}</strong>
            </div>
        @endforeach
        <dl>
            <div><dt>{{ __('store.checkout.subtotal') }}</dt><dd>{{ $math->format($quote->subtotal) }}</dd></div>
            <div><dt>{{ __('store.checkout.discount') }}</dt><dd>{{ $math->format($quote->discount) }}</dd></div>
            <div><dt>{{ __('store.checkout.shipping') }}</dt><dd data-shipping>{{ $math->format('0') }}</dd></div>
            <div><dt>{{ __('store.checkout.order_packaging') }}</dt><dd data-packaging>{{ $math->format('0') }}</dd></div>
            <div class="is-total"><dt>{{ __('store.checkout.total') }}</dt><dd data-total>{{ $math->format($goods) }}</dd></div>
        </dl>
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
            const packagingNode = summary.querySelector('[data-packaging]');
            if (packagingNode) packagingNode.textContent = format(packaging);
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
