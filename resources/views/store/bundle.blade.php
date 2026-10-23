@extends('layouts.store', ['title' => $bundle->name, 'seoKey' => 'bundle'])
@section('content')
<section class="mx-auto grid max-w-6xl gap-8 px-4 py-8 md:grid-cols-2">
    <div>
        @if($bundle->coverUrl())<img class="store-card mb-4 h-72 w-full object-cover" src="{{ $bundle->coverUrl() }}" alt="{{ $bundle->name }}">@endif
        <h1 class="text-3xl font-bold">{{ $bundle->name }}</h1>
        <p class="mt-3 opacity-80">{{ $bundle->description }}</p>
        <ul class="mt-4 space-y-1 text-sm">
            @foreach($bundle->items as $item)
                <li>{{ $item->product?->name }} × {{ $item->quantity }}</li>
            @endforeach
        </ul>
        <form class="mt-6" method="post" action="{{ route('cart.items.store') }}" @submit.prevent="add($el)">
            @csrf
            <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
            <input class="store-input" name="quantity" value="1">
            <button class="store-btn-primary mt-3" type="submit">{{ __('store.product.add') }}</button>
        </form>
    </div>
    @if($bundle->is_subscribable)
        <div class="store-card p-5">
            <h2 class="text-xl font-bold">{{ __('store.subscription.title') }}</h2>
            <p class="mt-2 text-sm opacity-80">{{ __('store.subscription.note') }}</p>
            @auth
                <form class="mt-4 space-y-3" method="post" action="{{ route('bundles.subscribe', $bundle) }}">
                    @csrf
                    <select class="store-input" name="interval">
                        <option value="weekly">{{ __('store.subscription.weekly') }}</option>
                        <option value="monthly">{{ __('store.subscription.monthly') }}</option>
                    </select>
                    <input class="store-input" name="quantity" value="1">
                    <input class="store-input" name="customer_name" value="{{ auth()->user()->name }}">
                    <input class="store-input" name="phone" value="{{ auth()->user()->phone }}" required>
                    <select class="store-input" name="city_id" required>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    <textarea class="store-input" name="address" required>{{ auth()->user()->address }}</textarea>
                    <button class="store-btn-accent" type="submit">{{ __('store.subscription.start') }}</button>
                </form>
            @else
                <a class="store-btn-primary mt-4" href="{{ route('login') }}">{{ __('store.nav.login') }}</a>
            @endauth
        </div>
    @endif
</section>
@endsection
