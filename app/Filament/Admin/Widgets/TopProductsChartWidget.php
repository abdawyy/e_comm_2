<?php

namespace App\Filament\Admin\Widgets;

use App\Services\SalesReport;
use Filament\Widgets\ChartWidget;

class TopProductsChartWidget extends ChartWidget
{
    protected ?string $maxHeight = '280px';

    public function getHeading(): ?string
    {
        return __('admin.stats.top_products');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $products = app(SalesReport::class)->summary()['products'];

        if ($products === []) {
            return [
                'labels' => [__('admin.stats.empty')],
                'datasets' => [[
                    'label' => __('admin.stats.revenue'),
                    'data' => [0],
                    'backgroundColor' => '#d6d3d1',
                ]],
            ];
        }

        return [
            'labels' => array_column($products, 'name'),
            'datasets' => [[
                'label' => __('admin.stats.revenue'),
                'data' => array_map(fn (array $row) => (float) $row['revenue'], $products),
                'backgroundColor' => '#1A2B28',
            ]],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}
