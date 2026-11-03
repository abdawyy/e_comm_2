<?php

namespace App\Filament\Admin\Widgets;

use App\Services\SalesReport;
use Filament\Widgets\ChartWidget;

class PaymentsChartWidget extends ChartWidget
{
    protected ?string $maxHeight = '260px';

    public function getHeading(): ?string
    {
        return __('admin.stats.by_payment');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $payments = app(SalesReport::class)->summary()['payments'];

        if ($payments === []) {
            return $this->emptyChart();
        }

        $labels = [];

        foreach (array_keys($payments) as $gateway) {
            $key = 'store.payment.'.$gateway;
            $label = __($key);
            $labels[] = $label === $key ? (string) $gateway : $label;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'data' => array_values($payments),
                'backgroundColor' => array_slice(ChartPalette::COLORS, 0, count($payments)),
            ]],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyChart(): array
    {
        return [
            'labels' => [__('admin.stats.empty')],
            'datasets' => [[
                'data' => [0],
                'backgroundColor' => ['#d6d3d1'],
            ]],
        ];
    }
}
