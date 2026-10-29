@extends('layouts.store', ['title' => $category->name, 'seoKey' => 'category'])
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8">
    <p class="store-kicker">{{ __('store.nav.shop') }}</p>
    <h1 class="mt-2 text-3xl font-bold">{{ $category->name }}</h1>
    <p class="mt-2 opacity-80">{{ $category->description }}</p>
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($products as $product)
            @include('store.partials.product-card', ['product' => $product])
        @empty
            <div class="store-card col-span-full p-8 text-center"><p>{{ __('store.shop.empty') }}</p></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
</section>
@endsection
