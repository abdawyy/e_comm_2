<?php

namespace App\Filament\Admin\Widgets;

use App\Services\SalesReport;
use Filament\Widgets\ChartWidget;

class CitiesChartWidget extends ChartWidget
{
    protected ?string $maxHeight = '280px';

    public function getHeading(): ?string
    {
        return __('admin.stats.by_city');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $cities = app(SalesReport::class)->summary()['cities'];

        if ($cities === []) {
            return [
                'labels' => [__('admin.stats.empty')],
                'datasets' => [[
                    'label' => __('admin.stats.orders'),
                    'data' => [0],
                    'backgroundColor' => '#d6d3d1',
                ]],
            ];
        }

        return [
            'labels' => array_column($cities, 'city'),
            'datasets' => [[
                'label' => __('admin.stats.orders'),
                'data' => array_column($cities, 'orders'),
                'backgroundColor' => '#C9A55C',
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
