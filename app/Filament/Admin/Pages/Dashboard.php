<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\CitiesChartWidget;
use App\Filament\Admin\Widgets\LatestOrdersWidget;
use App\Filament\Admin\Widgets\OrdersByStatusWidget;
use App\Filament\Admin\Widgets\PaymentsChartWidget;
use App\Filament\Admin\Widgets\ProductViewsChartWidget;
use App\Filament\Admin\Widgets\RevenueChartWidget;
use App\Filament\Admin\Widgets\SalesPeriodWidget;
use App\Filament\Admin\Widgets\SalesStatsWidget;
use App\Filament\Admin\Widgets\TopProductsChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            SalesPeriodWidget::class,
            SalesStatsWidget::class,
            RevenueChartWidget::class,
            OrdersByStatusWidget::class,
            PaymentsChartWidget::class,
            TopProductsChartWidget::class,
            CitiesChartWidget::class,
            ProductViewsChartWidget::class,
            LatestOrdersWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
