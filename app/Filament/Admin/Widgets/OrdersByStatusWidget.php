<?php

namespace App\Filament\Admin\Widgets;

use App\Services\SalesReport;
use Filament\Widgets\ChartWidget;

class OrdersByStatusWidget extends ChartWidget
{
    protected ?string $maxHeight = '260px';

    public function getHeading(): ?string
    {
        return __('admin.stats.by_status');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = app(SalesReport::class)->summary()['statuses'];

        if ($statuses === []) {
            return [
                'labels' => [__('admin.stats.empty')],
                'datasets' => [[
                    'data' => [0],
                    'backgroundColor' => ['#d6d3d1'],
                ]],
            ];
        }

        $labels = [];
        $colors = [];

        foreach (array_keys($statuses) as $status) {
            $key = 'admin.status.'.$status;
            $label = __($key);
            $labels[] = $label === $key ? (string) $status : $label;
            $colors[] = ChartPalette::STATUS[$status] ?? '#8C6A3D';
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'data' => array_values($statuses),
                'backgroundColor' => $colors,
            ]],
        ];
    }
}
