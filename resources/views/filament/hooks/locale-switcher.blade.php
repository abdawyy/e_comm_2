<a class="admin-locale" href="{{ request()->fullUrlWithQuery(['locale' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}">
    {{ app()->getLocale() === 'ar' ? 'EN' : 'ع' }}
</a>
<style>
    .admin-locale {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.4rem;
        height: 2.4rem;
        margin-inline-end: 0.4rem;
        border: 1px solid #d9d9de;
        border-radius: 999px;
        color: inherit;
        font-weight: 700;
        text-decoration: none;
    }
</style>
