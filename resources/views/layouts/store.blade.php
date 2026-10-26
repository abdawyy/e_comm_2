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
        $pageTitle = $title ?? null;
        $documentTitle = filled($pageTitle)
            ? $theme->brand_name.' — '.$pageTitle
            : ($seo?->meta_title ?: $theme->brand_name.' — '.\App\Support\Cms::text('home.hero_title', __('store.nav.home')));
        $metaDescription = filled($description ?? null)
            ? $description
            : ($seo?->meta_description ?: \App\Support\Cms::text('home.hero_subtitle', $theme->brand_name));
        $shareImage = $ogImage ?? ($seo?->ogUrl() ?: \App\Models\DesignAsset::urlFor('og_default'));
    @endphp
    <title>{{ $documentTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="{{ $theme->css('theme_color') }}">
    <meta property="og:title" content="{{ $documentTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_EG' : 'en_EG' }}">
    @if($shareImage)
        <meta property="og:image" content="{{ $shareImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $documentTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $shareImage }}">
    @endif
    @stack('head')
    @if($icon = \App\Models\DesignAsset::urlFor('favicon'))
        <link rel="icon" href="{{ $icon }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&family=IBM+Plex+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-brand: {{ $theme->css('primary') }};
            --color-brand-dark: {{ $theme->css('primary_dark') }};
            --color-brand-light: {{ $theme->css('primary_light') }};
            --color-accent: {{ $theme->css('accent') }};
            --color-accent-light: {{ $theme->css('accent_light') }};
            --color-canvas: {{ $theme->css('canvas') }};
            --color-surface: {{ $theme->css('surface') }};
            --color-border: {{ $theme->css('border') }};
            --color-success: {{ $theme->css('success') }};
            --color-danger: {{ $theme->css('danger') }};
            --color-canvas-base: {{ $theme->css('canvas') }};
            --color-surface-base: {{ $theme->css('surface') }};
            --color-border-base: {{ $theme->css('border') }};
        }
    </style>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && matchMedia('(prefers-color-scheme: dark)').matches)) {
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
<body class="store-shell" x-data="storefront">
    @include('store.partials.navbar')

    @if(session('status'))
        <div class="store-toast mx-auto mt-4 max-w-6xl rounded-xl px-4 py-3" x-data x-init="push(@js(session('status')))"></div>
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

    <footer class="store-footer">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-8">
            <div>
                <strong>{{ $theme->brand_name }}</strong>
                <p class="mt-1 text-sm opacity-80">{{ \App\Support\Cms::text('footer.blurb', __('store.footer.blurb')) }}</p>
            </div>
            @if($mark = \App\Models\DesignAsset::urlFor('footer_mark'))
                <img class="h-10" src="{{ $mark }}" alt="{{ \App\Models\DesignAsset::altFor('footer_mark') }}">
            @endif
        </div>
    </footer>

    <div class="fixed bottom-4 end-4 z-50 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="store-toast rounded-xl px-4 py-3 shadow-lg" x-text="toast.message"></div>
        </template>
    </div>
    @include('store.partials.signup-offer')
</body>
</html>
