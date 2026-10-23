<?php

namespace App\Filament\Admin\Widgets;

use App\Services\SalesReport;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('admin.stats.revenue_chart');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $series = app(SalesReport::class)->summary()['series'];

        return [
            'labels' => array_column($series, 'label'),
            'datasets' => [
                [
                    'label' => __('admin.stats.revenue'),
                    'data' => array_map(fn ($row) => (float) $row['revenue'], $series),
                    'backgroundColor' => 'rgba(22, 163, 74, 0.45)',
                ],
                [
                    'label' => __('admin.stats.orders'),
                    'data' => array_column($series, 'orders'),
                    'type' => 'line',
                ],
            ],
        ];
    }
}
