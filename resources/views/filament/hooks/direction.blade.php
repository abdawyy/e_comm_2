<script>
    document.documentElement.setAttribute('dir', @js(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'));
    document.documentElement.setAttribute('lang', @js(app()->getLocale()));
</script>
<style>
    :root { --color-brand: {{ \App\Models\SiteTheme::current()->css('primary') }}; }
</style>
