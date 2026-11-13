@extends('layouts.store')
@section('content')
@section('content')
<style>
    .store-hero {
        width: 100% !important;
        max-width: none !important;
        min-height: 34rem;
        display: flex !important;
        align-items: center;
        justify-content: flex-start;
        margin: 0 !important;
        padding: 4.5rem max(1.25rem, calc((100% - 76rem) / 2));
        background-color: #f4efe6;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        background-image: linear-gradient(90deg, rgb(244 239 230 / 92%), rgb(244 239 230 / 8%)), var(--hero);
    }
    html[dir="rtl"] .store-hero {
        background-image: linear-gradient(270deg, rgb(244 239 230 / 92%), rgb(244 239 230 / 8%)), var(--hero);
    }
    html.dark .store-hero {
        background-color: #121418;
        background-image: linear-gradient(90deg, rgb(18 20 24 / 88%), rgb(18 20 24 / 12%)), var(--hero);
    }
    html.dark[dir="rtl"] .store-hero {
        background-image: linear-gradient(270deg, rgb(18 20 24 / 88%), rgb(18 20 24 / 12%)), var(--hero);
    }
    .store-hero-copy { max-width: 36rem; text-align: start; color: #131316; }
    html.dark .store-hero-copy,
    html.dark .store-hero h1,
    html.dark .store-hero .store-lead,
    html.dark .store-hero .store-kicker { color: #f7f4ee !important; }
</style>
<section class="store-hero" @if($hero = \App\Models\DesignAsset::urlFor('hero')) style="--hero: url('{{ str_replace(["'", '\\'], '', $hero) }}')" @endif>
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
