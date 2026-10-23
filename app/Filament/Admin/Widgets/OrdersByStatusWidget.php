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

        return [
            'labels' => array_keys($statuses),
            'datasets' => [[
                'data' => array_values($statuses),
            ]],
        ];
    }
}
