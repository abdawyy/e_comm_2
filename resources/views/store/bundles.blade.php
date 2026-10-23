@extends('layouts.store', ['title' => __('store.nav.bundles'), 'seoKey' => 'bundles'])
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8">
    <h1 class="text-3xl font-bold">{{ __('store.nav.bundles') }}</h1>
    <div class="mt-6 grid gap-4 md:grid-cols-3">
        @foreach($bundles as $bundle)
            <article class="store-card flex flex-col p-4">
                <a class="block" href="{{ route('bundles.show', $bundle) }}">
                    @if($bundle->coverUrl())<img class="mb-3 h-40 w-full rounded-lg object-cover" src="{{ $bundle->coverUrl() }}" alt="{{ $bundle->name }}">@endif
                    <h2 class="font-semibold">{{ $bundle->name }}</h2>
                    <p class="mt-2 font-bold">{{ app(\App\Services\DecimalMath::class)->format((string) $bundle->price) }}</p>
                </a>
                <form class="mt-4" method="post" action="{{ route('cart.items.store') }}" @submit.prevent="add($el)">
                    @csrf
                    <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button class="store-btn-primary w-full" type="submit">{{ __('store.product.add') }}</button>
                </form>
            </article>
        @endforeach
    </div>
</section>
@endsection
