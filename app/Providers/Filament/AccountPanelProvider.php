<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetLocale;
use App\Models\DesignAsset;
use App\Models\SiteTheme;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AccountPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('account')
            ->path('my')
            ->login()
            ->maxContentWidth(Width::Full)
            ->sidebarFullyCollapsibleOnDesktop()
            ->brandName(fn (): string => SiteTheme::current()->brand_name)
            ->darkMode()
            ->brandLogo(fn (): ?string => DesignAsset::urlFor('logo'))
            ->darkModeBrandLogo(fn (): ?string => DesignAsset::urlFor('logo_dark') ?: DesignAsset::urlFor('logo'))
            ->favicon(fn (): ?string => DesignAsset::urlFor('favicon'))
            ->colors(fn (): array => [
                'primary' => Color::hex(SiteTheme::current()->css('primary')),
            ])
            ->discoverResources(in: app_path('Filament/Account/Resources'), for: 'App\\Filament\\Account\\Resources')
            ->discoverPages(in: app_path('Filament/Account/Pages'), for: 'App\\Filament\\Account\\Pages')
            ->discoverWidgets(in: app_path('Filament/Account/Widgets'), for: 'App\\Filament\\Account\\Widgets')
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.hooks.direction'))
            ->renderHook(PanelsRenderHook::TOPBAR_END, fn () => view('filament.hooks.theme-switcher'))
            ->renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, fn () => view('filament.hooks.theme-switcher', ['fixed' => true]))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
