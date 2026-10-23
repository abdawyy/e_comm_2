<?php

namespace App\Filament\Admin\Widgets;

use App\Services\DecimalMath;
use App\Services\SalesReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $summary = app(SalesReport::class)->summary();
        $math = app(DecimalMath::class);

        return [
            Stat::make(__('admin.stats.revenue'), $math->format($summary['revenue']))->description($this->delta($summary['revenue_change'])),
            Stat::make(__('admin.stats.orders'), (string) $summary['orders'])->description($this->delta($summary['orders_change'])),
            Stat::make(__('admin.stats.average'), $math->format($summary['average']))->description($this->delta($summary['average_change'])),
            Stat::make(__('admin.stats.pending'), (string) $summary['pending'])->description($this->delta($summary['pending_change'])),
        ];
    }

    private function delta(?string $change): string
    {
        if ($change === null) {
            return __('admin.stats.no_baseline');
        }

        return $change.'%';
    }
}
