@php
    $accountName = auth()->check() ? \Illuminate\Support\Str::before(auth()->user()->name, ' ') : null;
@endphp
<header class="store-header">
    <div class="store-nav">
        <button class="store-icon-btn store-menu-btn" type="button" @click="drawer = true" aria-label="{{ __('store.nav.menu') }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>

        <a href="{{ route('home') }}" class="store-brand">
            @if($logo = \App\Models\DesignAsset::urlFor('logo'))
                <img class="store-logo-light" src="{{ $logo }}" alt="" onerror="this.remove()">
            @endif
            @if($logoDark = \App\Models\DesignAsset::urlFor('logo_dark'))
                <img class="store-logo-dark" src="{{ $logoDark }}" alt="" onerror="this.remove()">
            @endif
            <span class="store-brand-name">{{ $theme->brand_name }}</span>
        </a>

        <nav class="store-nav-links" aria-label="{{ __('store.nav.menu') }}">
            <a class="store-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">{{ __('store.nav.home') }}</a>
            <a class="store-nav-link {{ request()->routeIs('shop', 'products.*', 'categories.*') ? 'is-active' : '' }}" href="{{ route('shop') }}">{{ __('store.nav.shop') }}</a>
            <a class="store-nav-link {{ request()->routeIs('bundles.*') ? 'is-active' : '' }}" href="{{ route('bundles.index') }}">{{ __('store.nav.bundles') }}</a>
            <a class="store-nav-link {{ request()->routeIs('orders.track') ? 'is-active' : '' }}" href="{{ route('orders.track') }}">{{ __('store.nav.track') }}</a>
        </nav>

        <div class="store-nav-search">
            <livewire:storefront.search-bar />
        </div>

        <div class="store-nav-tools">
            <a class="store-lang" href="{{ request()->fullUrlWithQuery(['locale' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}">{{ app()->getLocale() === 'ar' ? 'EN' : 'ع' }}</a>
            <button type="button" class="store-icon-btn" @click="toggleDark()" aria-label="{{ __('store.nav.theme') }}">
                <svg x-show="!dark" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6l1.4 1.4M17 17l1.4 1.4M18.4 5.6 17 7M7 17l-1.4 1.4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                <svg x-show="dark" x-cloak viewBox="0 0 24 24" aria-hidden="true"><path d="M16 3.2A8.5 8.5 0 1 0 20.8 14 7 7 0 0 1 16 3.2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
            </button>
            <a class="store-account {{ request()->routeIs('login') || request()->is('my*') ? 'is-active' : '' }}" href="{{ auth()->check() ? url('/my') : route('login') }}">
                <span class="store-icon-btn" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M5.5 19.2a6.5 6.5 0 0 1 13 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <span class="store-account-label">{{ $accountName ?: __('store.nav.login') }}</span>
            </a>
            <a class="store-icon-btn store-cart-link {{ request()->routeIs('cart.*') ? 'is-active' : '' }}" href="{{ route('cart.show') }}" aria-label="{{ __('store.nav.cart') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h15l-1.4 8.2a2 2 0 0 1-2 1.6H9.2a2 2 0 0 1-2-1.6L5.2 4.8H3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="20" r="1.2" fill="currentColor"/><circle cx="17" cy="20" r="1.2" fill="currentColor"/></svg>
                <span class="store-cart-count"><livewire:storefront.cart-count /></span>
            </a>
        </div>
    </div>
</header>

<div class="store-drawer-backdrop" x-show="drawer" x-cloak x-transition.opacity @click="drawer = false">
    <aside class="store-drawer" @click.stop x-show="drawer" x-transition.opacity.duration.150ms>
        <div class="store-drawer-head">
            <strong>{{ $theme->brand_name }}</strong>
            <button class="store-icon-btn" type="button" @click="drawer = false" aria-label="{{ __('store.nav.close') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
        <nav class="store-drawer-links">
            <a class="{{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">{{ __('store.nav.home') }}</a>
            <a class="{{ request()->routeIs('shop', 'products.*', 'categories.*') ? 'is-active' : '' }}" href="{{ route('shop') }}">{{ __('store.nav.shop') }}</a>
            <a class="{{ request()->routeIs('bundles.*') ? 'is-active' : '' }}" href="{{ route('bundles.index') }}">{{ __('store.nav.bundles') }}</a>
            <a class="{{ request()->routeIs('orders.track') ? 'is-active' : '' }}" href="{{ route('orders.track') }}">{{ __('store.nav.track') }}</a>
            <a class="{{ request()->is('my*') ? 'is-active' : '' }}" href="{{ auth()->check() ? url('/my') : route('login') }}">{{ auth()->check() ? __('store.nav.account') : __('store.nav.login') }}</a>
            <a class="{{ request()->routeIs('cart.*') ? 'is-active' : '' }}" href="{{ route('cart.show') }}">{{ __('store.nav.cart') }}</a>
        </nav>
        <div class="mt-4"><livewire:storefront.search-bar /></div>
    </aside>
</div>
