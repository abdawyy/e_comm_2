<div class="staff-guide">
    <header class="staff-guide-hero">
        <div>
            <p>{{ config('store.brand_name', 'Atlas Market') }}</p>
            <h1>{{ __('admin.pages.guide') }}</h1>
        </div>
        <div class="staff-guide-links">
            <a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}">English</a>
            <a href="{{ request()->fullUrlWithQuery(['locale' => 'ar']) }}">العربية</a>
            <a class="is-download" href="{{ route('admin.guide.pdf') }}">{{ __('admin.guide.download') }}</a>
        </div>
    </header>
    <div class="staff-guide-body">
        @include('filament.admin.partials.guide-body', ['locale' => app()->getLocale()])
    </div>
</div>
<style>
    .staff-guide { display: grid; gap: 1rem; }
    .staff-guide-hero, .staff-guide-body section {
        border: 1px solid rgb(226 232 240);
        border-radius: 1.15rem;
        background: #fff;
        padding: 1.15rem 1.25rem;
    }
    .dark .staff-guide-hero, .dark .staff-guide-body section {
        border-color: rgb(255 255 255 / 10%);
        background: rgb(17 24 39);
        color: rgb(243 244 246);
    }
    .staff-guide-hero {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
        border-top: 6px solid #C9A55C;
    }
    .staff-guide-hero p { margin: 0; font-size: 0.78rem; font-weight: 750; letter-spacing: 0.08em; text-transform: uppercase; color: #8C6A3D; }
    .staff-guide-hero h1 { margin: 0.25rem 0 0; font-size: 1.7rem; font-weight: 750; }
    .staff-guide-links { display: flex; flex-wrap: wrap; gap: 0.45rem; align-items: center; }
    .staff-guide-links a {
        border-radius: 999px;
        background: rgb(241 245 249);
        padding: 0.45rem 0.85rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: inherit;
        text-decoration: none;
    }
    .dark .staff-guide-links a { background: rgb(31 41 55); }
    .staff-guide-links .is-download { background: #1A2B28; color: #F5EFE6; }
    .staff-guide-body { display: grid; gap: 0.85rem; }
    .staff-guide-body section { border-inline-start: 4px solid #1A2B28; }
    .staff-guide-body h2 { margin: 0 0 0.45rem; font-size: 1.05rem; font-weight: 750; }
    .staff-guide-body p, .staff-guide-body li { margin: 0.35rem 0 0; line-height: 1.55; }
    .staff-guide-body ul { margin: 0.4rem 0 0; padding-inline-start: 1.1rem; }
</style>
