<?php

namespace App\Filament\Admin\Widgets;

use App\Services\SalesReport;
use Filament\Widgets\ChartWidget;

class LatestOrdersWidget extends ChartWidget
{
    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('admin.stats.latest_orders');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $orders = app(SalesReport::class)->summary()['latest'];
        $labels = [];
        $totals = [];
        $colors = [];

        foreach ($orders as $order) {
            $statusKey = 'admin.status.'.$order->status;
            $status = __($statusKey);
            $labels[] = $order->customer_name.' · '.($status === $statusKey ? $order->status : $status);
            $totals[] = (float) $order->total;
            $colors[] = ChartPalette::STATUS[$order->status] ?? '#8C6A3D';
        }

        if ($labels === []) {
            return [
                'labels' => [__('admin.stats.empty')],
                'datasets' => [[
                    'label' => __('admin.fields.total'),
                    'data' => [0],
                    'backgroundColor' => ['#d6d3d1'],
                ]],
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => __('admin.fields.total'),
                'data' => $totals,
                'backgroundColor' => $colors,
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
