<?php

namespace App\Filament\Account\Pages;

use App\Filament\Account\Widgets\RecentOrdersWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function getWidgets(): array
    {
        return [
            RecentOrdersWidget::class,
        ];
    }
}
