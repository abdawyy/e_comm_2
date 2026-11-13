@extends('layouts.store', ['title' => $product->name, 'seoKey' => 'product'])
@section('content')
@php
    $math = app(\App\Services\DecimalMath::class);
    $shots = collect();
    if ($cover = $product->coverUrl()) {
        $shots->push($cover);
    }
    foreach ($product->images as $image) {
        $url = $image->url();
        if ($url && ! $shots->contains($url)) {
            $shots->push($url);
        }
    }
    $inStock = ! $product->track_stock || $math->comp((string) $product->stock_quantity, '0') > 0;
@endphp
<section class="product-page">
    <nav class="product-crumb" aria-label="{{ __('store.nav.shop') }}">
        <a href="{{ route('home') }}">{{ __('store.nav.home') }}</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('shop') }}">{{ __('store.nav.shop') }}</a>
        @if($product->category)
            <span aria-hidden="true">/</span>
            <a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
        @endif
        <span aria-hidden="true">/</span>
        <span>{{ $product->name }}</span>
    </nav>

    <div class="product-layout">
        <div class="product-gallery {{ $shots->isEmpty() ? 'is-fallback' : '' }}" x-data="{ shot: @js($shots->first()) }">
            <div class="product-stage">
                <span class="product-stage-kicker">{{ $product->category?->name }}</span>
                <strong>{{ $product->name }}</strong>
                <span>{{ $math->format((string) $product->price_per_unit) }} / {{ $product->unit }}</span>
            </div>
            @if($shots->isNotEmpty())
                <img class="product-shot" :src="shot" src="{{ $shots->first() }}" alt="{{ $product->name }}" onerror="this.closest('.product-gallery').classList.add('is-fallback'); this.remove()">
                @if($shots->count() > 1)
                    <div class="product-thumbs">
                        @foreach($shots as $shot)
                            <button type="button" @click="shot = @js($shot)">
                                <img src="{{ $shot }}" alt="{{ $product->name }}">
                            </button>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <div class="product-summary">
            @if($product->category)
                <a class="store-kicker" href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
            @endif
            <h1>{{ $product->name }}</h1>
            <p class="product-lead">{{ $product->description }}</p>
            <div class="product-facts">
                <span class="product-chip">{{ __('store.product.sku') }} {{ $product->sku }}</span>
                <span class="product-chip">{{ $product->unit }}</span>
                <span class="product-chip {{ $inStock ? 'is-ok' : 'is-bad' }}">{{ $inStock ? __('store.product.in_stock') : __('store.product.out_of_stock') }}</span>
            </div>
            <livewire:storefront.product-configurator :product="$product" />
        </div>
    </div>
</section>
<section class="product-reviews">
    @php
        $reviews = $product->approvedReviews;
        $average = $reviews->isEmpty() ? null : number_format($reviews->avg('rating'), 1);
    @endphp
    <h2>{{ __('store.product.reviews') }}@if($average) <span class="product-stars">★ {{ $average }}</span>@endif</h2>
    @forelse($reviews as $review)
        <article class="product-review">
            <strong>{{ $review->author_name }}</strong>
            <div class="product-stars">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</div>
            <p>{{ $review->body }}</p>
        </article>
    @empty
        <p>{{ __('store.product.no_reviews') }}</p>
    @endforelse
    <form class="product-review-form" method="post" action="{{ route('products.reviews.store', $product) }}">
        @csrf
        <h3>{{ __('store.product.write_review') }}</h3>
        <label>
            <span>{{ __('store.product.your_name') }}</span>
            <input class="store-input" name="author_name" value="{{ old('author_name', auth()->user()->name ?? '') }}" required>
        </label>
        <label>
            <span>{{ __('store.product.stars') }}</span>
            <select class="store-input" name="rating" required>
                @for($star = 5; $star >= 1; $star--)
                    <option value="{{ $star }}" @selected((int) old('rating', 5) === $star)>{{ $star }}</option>
                @endfor
            </select>
        </label>
        <label>
            <span>{{ __('store.product.your_review') }}</span>
            <textarea class="store-input" name="body" rows="4" required>{{ old('body') }}</textarea>
        </label>
        <button class="store-btn-primary" type="submit">{{ __('store.product.write_review') }}</button>
    </form>
</section>
@if($related->isNotEmpty())
<section class="product-related">
    <h2>{{ $relatedTitle }}</h2>
    <div class="product-related-grid">
        @foreach($related as $item)
            @include('store.partials.product-card', ['product' => $item])
        @endforeach
    </div>
</section>
@endif
@endsection
