<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetLocale;
use App\Models\DesignAsset;
use App\Models\SiteTheme;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
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
            ->topNavigation()
            ->globalSearch(false)
            ->maxContentWidth(Width::SevenExtraLarge)
            ->brandName(fn (): string => SiteTheme::current()->brand_name)
            ->darkMode(false)
            ->brandLogo(fn (): ?string => DesignAsset::urlFor('logo'))
            ->favicon(fn (): ?string => DesignAsset::urlFor('favicon'))
            ->colors([
                'primary' => Color::hex('#131316'),
                'gray' => Color::hex('#212a2f'),
            ])
            ->navigationItems([
                NavigationItem::make('shop')
                    ->label(fn (): string => __('store.nav.shop'))
                    ->url(fn (): string => url('/'))
                    ->icon(Heroicon::OutlinedShoppingBag)
                    ->sort(-10),
            ])
            ->discoverResources(in: app_path('Filament/Account/Resources'), for: 'App\\Filament\\Account\\Resources')
            ->discoverPages(in: app_path('Filament/Account/Pages'), for: 'App\\Filament\\Account\\Pages')
            ->discoverWidgets(in: app_path('Filament/Account/Widgets'), for: 'App\\Filament\\Account\\Widgets')
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.hooks.direction'))
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.account.theme'))
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
