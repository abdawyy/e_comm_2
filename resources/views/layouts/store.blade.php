<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $theme = \App\Models\SiteTheme::current();
        $seo = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('seo_settings')) {
                $seo = \App\Models\SeoSetting::query()->where('route_key', $seoKey ?? 'home')->first();
            }
        } catch (\Throwable $exception) {
            $seo = null;
        }
    @endphp
    <title>{{ $theme->brand_name }} — {{ $title ?? \App\Support\Cms::text('home.hero_title', __('store.nav.home')) }}</title>
    <meta name="description" content="{{ $seo?->meta_description ?? \App\Support\Cms::text('home.hero_subtitle', $theme->brand_name) }}">
    <meta name="theme-color" content="{{ $theme->css('theme_color') }}">
    <meta property="og:title" content="{{ $theme->brand_name }}">
    <meta property="og:image" content="{{ $seo?->ogUrl() ?: \App\Models\DesignAsset::urlFor('og_default') }}">
    @if($icon = \App\Models\DesignAsset::urlFor('favicon'))
        <link rel="icon" href="{{ $icon }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&family=IBM+Plex+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-brand: #131316;
            --color-brand-dark: #131316;
            --color-brand-light: #2a2a2e;
            --color-accent: #eeddb0;
            --color-accent-light: #f7f1e4;
            --color-canvas: #ffffff;
            --color-surface: #ffffff;
            --color-border: #e6e6e6;
            --color-success: #131316;
            --color-danger: #b42318;
            --color-canvas-base: #ffffff;
            --color-surface-base: #ffffff;
            --color-border-base: #e6e6e6;
        }
        .store-hero-visual {
            background: #f7f1e4;
            border: 0;
            box-shadow: 0 24px 50px rgb(19 19 22 / 8%);
        }
        .store-hero-visual div { background: transparent; }
        html.dark .store-hero-visual,
        html.dark .store-hero-visual div,
        html.dark .store-hero-visual strong,
        html.dark .store-hero-visual p {
            color: #f4fbf6;
        }
        html.dark .store-hero-visual {
            background: #141816;
            border-color: #2a2e33;
        }
        html.dark .store-hero-visual div {
            background: linear-gradient(transparent, rgb(20 24 22 / 88%));
        }
        .store-departments { display: flex; flex-wrap: wrap; gap: 1.5rem 2rem; margin-top: 1.5rem; justify-content: center; }
        .store-department { display: grid; justify-items: center; gap: 0.75rem; width: 8.5rem; color: #131316; font-weight: 700; text-align: center; }
        .store-department-icon { display: grid; width: 8.5rem; height: 8.5rem; place-items: center; overflow: hidden; border-radius: 999px; background: #f3f3f3; }
        .store-hero h1,
        .store-hero .store-lead,
        .store-hero .store-kicker,
        .store-hero-copy {
            color: #f7f4ee !important;
            text-shadow: 0 2px 16px rgb(0 0 0 / 45%);
        }
    </style>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @if($ga = config('store.ga4_measurement_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
    @endif
    @if($pixel = config('store.meta_pixel_id'))
        <script>window.metaPixelId = @json($pixel);</script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="store-shell" x-data="storefront" :class="{ 'menu-open': drawer }">
    @include('store.partials.announcement')
    @include('store.partials.navbar')
    @include('store.partials.department-rail')

    @if(session('status'))
        <div class="hidden" x-init="window.dispatchEvent(new CustomEvent('toast', { detail: { message: @js(session('status')) } }))"></div>
    @endif
    @if(session('error'))
        <div class="mx-auto mt-4 max-w-6xl rounded-xl px-4 py-3 text-white" style="background: var(--color-danger)">{{ session('error') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    <section class="store-trust">
        <div class="mx-auto grid max-w-6xl gap-4 px-4 py-6 text-sm md:grid-cols-3">
            <div>{{ \App\Support\Cms::text('trust.shipping', __('store.trust.shipping')) }}</div>
            <div>{{ \App\Support\Cms::text('trust.payment', __('store.trust.payment')) }}</div>
            <div>{{ \App\Support\Cms::text('trust.support', __('store.trust.support')) }}</div>
        </div>
    </section>

    <style>
        .store-footer { background: #ffffff; color: #131316; border-top: 1px solid #e6e6e6; }
        .store-footer > div { padding-block: 1.15rem; }
        .store-footer p { color: #3a3a40; }
        .store-social a { color: #131316; background: #ffffff; border-color: #d9d9de; }
        .store-social a:hover { background: #131316; border-color: #131316; color: #ffffff; }
        html.dark .store-footer { background: #121418; color: #f4fbf6; border-top-color: #2a2e33; }
        html.dark .store-footer p { color: #d5ddd8; }
        html.dark .store-social a { color: #f4fbf6; background: transparent; border-color: #8d9790; }
        html.dark .store-social a:hover { background: #f4fbf6; border-color: #f4fbf6; color: #121418; }
    </style>
    <footer class="store-footer">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-8">
            <div>
                <strong>{{ $theme->brand_name }}</strong>
                <p class="mt-1 text-sm opacity-80">{{ \App\Support\Cms::text('footer.blurb', __('store.footer.blurb')) }}</p>
            </div>
            @include('store.partials.social')
            @if($mark = \App\Models\DesignAsset::urlFor('footer_mark'))
                <img class="h-10" src="{{ $mark }}" alt="{{ \App\Models\DesignAsset::altFor('footer_mark') }}">
            @endif
        </div>
    </footer>

    <div class="store-toasts">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="store-toast rounded-xl px-4 py-3 shadow-lg" x-text="toast.message"></div>
        </template>
    </div>
    @include('store.partials.whatsapp')
    @include('store.partials.signup-offer')
</body>
</html>
