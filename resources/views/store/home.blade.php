@extends('layouts.store')
@section('content')
<style>
    .store-photo-band { position: relative; isolation: isolate; background: #1c140f center / cover no-repeat; color: #f7f4ee; }
    .store-photo-band::before { content: ""; position: absolute; inset: 0; z-index: 0; pointer-events: none; background: rgb(20 14 10 / 40%); }
    .store-photo-band > * { position: relative; z-index: 1; }
    .store-photo-band .store-hero { background: transparent; min-height: 28rem; }
    .store-photo-band .store-hero-photo { display: none; }
    .store-photo-band .store-section { background: transparent; }
    .store-photo-band .store-section-title,
    .store-photo-band .store-department,
    .store-photo-band .store-department span { color: #f7f4ee; text-shadow: 0 2px 12px rgb(0 0 0 / 55%); }
    .store-hero-copy { position: relative; z-index: 1; }
</style>
@php($hero = str_replace(["'", '\\'], '', \App\Models\DesignAsset::urlFor('hero') ?: '/storage/design/hero.jpg'))
<div class="store-photo-band" style="background-image: url('{{ $hero }}')">
<section class="store-hero">
    <div class="store-hero-copy">
        <p class="store-kicker">{{ \App\Support\Cms::text('home.kicker', __('store.home.kicker')) }}</p>
        <h1>{{ \App\Support\Cms::text('home.hero_title', __('store.home.title')) }}</h1>
        <p class="store-lead">{{ \App\Support\Cms::text('home.hero_subtitle', __('store.home.subtitle')) }}</p>
        <div class="store-hero-actions">
            <a class="store-btn-primary" href="{{ route('shop') }}">{{ __('store.home.shop_cta') }}</a>
            <a class="store-btn-accent" href="{{ route('bundles.index') }}">{{ __('store.home.bundle_cta') }}</a>
        </div>
    </div>
</section>

<section class="store-section">
    <h2 class="store-section-title store-departments-title">{{ __('store.home.categories') }}</h2>
    <div class="store-departments">
        @foreach($departments as $category)
                    <a class="store-department" href="{{ $category->destination() }}">
                <span class="store-department-icon">
                    <img src="{{ $category->iconUrl() }}" alt="{{ $category->name }}">
                </span>
                <span>{{ $category->name }}</span>
            </a>
        @endforeach
    </div>
</section>
</div>

@if($banners->isNotEmpty())
<section class="mx-auto max-w-6xl px-4 pb-8">
    <div class="grid gap-4 md:grid-cols-2">
        @foreach($banners as $banner)
            <article class="store-card p-6">
                @if($banner->badge)<span class="store-badge">{{ $banner->badge }}</span>@endif
                <h2 class="mt-3 text-2xl font-bold">{{ $banner->title }}</h2>
                <p class="mt-2">{{ $banner->subtitle }}</p>
                @if($banner->imageUrl())<img class="mt-4 rounded-xl" src="{{ $banner->imageUrl() }}" alt="">@endif
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($banner->localizedButtons() as $button)
                        <a class="store-btn-ghost" href="{{ $button['url'] }}">{{ $button['label'] }}</a>
                    @endforeach
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="store-section">
    <h2 class="store-section-title">{{ __('store.home.featured') }}</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($featured as $product)
            @include('store.partials.product-card', ['product' => $product])
        @empty
            <p>{{ __('store.shop.empty') }}</p>
        @endforelse
    </div>
</section>
@endsection
