<?php

namespace App\Filament\Admin\Widgets;

use App\Services\SalesReport;
use Filament\Widgets\ChartWidget;

class ProductViewsChartWidget extends ChartWidget
{
    protected ?string $maxHeight = '260px';

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('admin.stats.product_views');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $views = app(SalesReport::class)->summary()['views'];

        if ($views === []) {
            return [
                'labels' => [__('admin.stats.empty')],
                'datasets' => [[
                    'label' => __('admin.stats.product_views'),
                    'data' => [0],
                    'backgroundColor' => '#d6d3d1',
                ]],
            ];
        }

        return [
            'labels' => array_column($views, 'name'),
            'datasets' => [[
                'label' => __('admin.stats.product_views'),
                'data' => array_column($views, 'views'),
                'backgroundColor' => '#2F4F47',
            ]],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}
