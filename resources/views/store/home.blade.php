@extends('layouts.store')
@section('content')
@php($theme = \App\Models\SiteTheme::current())
<section class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-12 md:grid-cols-2">
    <div>
        <p class="store-kicker">{{ \App\Support\Cms::text('home.kicker', __('store.home.kicker')) }}</p>
        <h1 class="mt-3 text-4xl font-bold md:text-5xl">{{ \App\Support\Cms::text('home.hero_title', __('store.home.title')) }}</h1>
        <p class="mt-4 text-lg opacity-80">{{ \App\Support\Cms::text('home.hero_subtitle', __('store.home.subtitle')) }}</p>
        <div class="mt-6 flex gap-3">
            <a class="store-btn-primary" href="{{ route('shop') }}">{{ __('store.home.shop_cta') }}</a>
            <a class="store-btn-accent" href="{{ route('bundles.index') }}">{{ __('store.home.bundle_cta') }}</a>
        </div>
    </div>
    <div class="store-card min-h-72" style="background: linear-gradient(160deg, var(--color-brand-dark), var(--color-brand-light));">
        @if($hero = \App\Models\DesignAsset::urlFor('hero'))
            <img class="h-full w-full object-cover" src="{{ $hero }}" alt="{{ \App\Models\DesignAsset::altFor('hero') }}">
        @endif
    </div>
</section>

@if($banners->isNotEmpty())
<section class="mx-auto max-w-6xl px-4 pb-8">
    <div class="grid gap-4 md:grid-cols-2">
        @foreach($banners as $banner)
            <article class="store-card p-6" style="background: linear-gradient(120deg, {{ $banner->gradient_start ?: 'var(--color-brand)' }}, {{ $banner->gradient_end ?: 'var(--color-accent)' }}); color: white;">
                @if($banner->badge)<span class="store-badge">{{ $banner->badge }}</span>@endif
                <h2 class="mt-3 text-2xl font-bold">{{ $banner->title }}</h2>
                <p class="mt-2">{{ $banner->subtitle }}</p>
                @if($banner->imageUrl())<img class="mt-4 rounded-xl" src="{{ $banner->imageUrl() }}" alt="">@endif
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($banner->localizedButtons() as $button)
                        <a class="store-btn-ghost" style="color: white; border-color: white" href="{{ $button['url'] }}">{{ $button['label'] }}</a>
                    @endforeach
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="mx-auto max-w-6xl px-4 py-8">
    <h2 class="text-2xl font-bold">{{ __('store.home.categories') }}</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($categories as $category)
            <a class="store-card p-4" href="{{ route('categories.show', $category) }}">
                @if($category->imageUrl())<img class="mb-3 h-28 w-full rounded-lg object-cover" src="{{ $category->imageUrl() }}" alt="">@endif
                <strong>{{ $category->name }}</strong>
            </a>
        @endforeach
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-8">
    <h2 class="text-2xl font-bold">{{ __('store.home.featured') }}</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($featured as $product)
            @include('store.partials.product-card', ['product' => $product])
        @empty
            <p>{{ __('store.shop.empty') }}</p>
        @endforelse
    </div>
</section>
@endsection
