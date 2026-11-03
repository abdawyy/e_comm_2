<?php

namespace App\Filament\Admin\Pages;

use App\Services\AnalyticsPeriod;
use App\Services\DecimalMath;
use App\Services\SalesReport;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SalesAnalytics extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $slug = 'sales';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.analytics');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.sales');
    }

    public function getTitle(): string
    {
        return __('admin.pages.sales');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('ViewAny:Order');
    }

    public function setPeriod(string $period): void
    {
        app(AnalyticsPeriod::class)->set($period);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.admin.sales')->viewData(function () {
                $summary = app(SalesReport::class)->summary();
                $math = app(DecimalMath::class);

                return [
                    'period' => app(AnalyticsPeriod::class)->key(),
                    'options' => AnalyticsPeriod::OPTIONS,
                    'revenue' => $math->format($summary['revenue']),
                    'orders' => $summary['orders'],
                    'average' => $math->format($summary['average']),
                    'pending' => $summary['pending'],
                    'series' => $summary['series'],
                    'products' => $summary['products'],
                    'cities' => $summary['cities'],
                    'coupons' => $summary['coupons'],
                    'payments' => $summary['payments'],
                    'math' => $math,
                ];
            }),
        ]);
    }
}
