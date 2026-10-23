<div
    @class(['fi-theme-switcher', 'fi-theme-switcher-fixed' => $fixed ?? false])
    x-data="{ theme: localStorage.getItem('theme') || 'system' }"
>
    <button type="button" class="fi-theme-switcher-btn" aria-label="{{ __('store.theme.light') }}" x-on:click="theme = 'light'; $dispatch('theme-changed', 'light')" x-bind:class="{ 'fi-active': theme === 'light' }">
        {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Sun) }}
    </button>
    <button type="button" class="fi-theme-switcher-btn" aria-label="{{ __('store.theme.dark') }}" x-on:click="theme = 'dark'; $dispatch('theme-changed', 'dark')" x-bind:class="{ 'fi-active': theme === 'dark' }">
        {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Moon) }}
    </button>
    <button type="button" class="fi-theme-switcher-btn" aria-label="{{ __('store.theme.system') }}" x-on:click="theme = 'system'; $dispatch('theme-changed', 'system')" x-bind:class="{ 'fi-active': theme === 'system' }">
        {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::ComputerDesktop) }}
    </button>
</div>
<style>
    .fi-theme-switcher-fixed {
        position: fixed;
        top: 1rem;
        inset-inline-end: 1rem;
        z-index: 40;
    }
</style>
