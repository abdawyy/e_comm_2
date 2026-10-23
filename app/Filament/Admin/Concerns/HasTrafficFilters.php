<?php

namespace App\Filament\Admin\Concerns;

use App\Models\Order;
use App\Models\SiteVisitor;
use App\Services\AnalyticsPeriod;
use App\Services\TrafficReport;

trait HasTrafficFilters
{
    public string $period = '30';

    public string $from = '';

    public string $until = '';

    public string $device = 'all';

    public string $place = 'all';

    public function mountHasTrafficFilters(): void
    {
        $saved = session('traffic.filters', []);
        $this->period = $this->allowedPeriod((string) ($saved['period'] ?? '30'));
        $this->from = (string) ($saved['from'] ?? '');
        $this->until = (string) ($saved['until'] ?? '');
        $this->device = (string) ($saved['device'] ?? 'all');
        $this->place = (string) ($saved['place'] ?? 'all');
    }

    public function setPeriod(string $period): void
    {
        $this->period = $this->allowedPeriod($period);
        $this->rememberFilters();
    }

    public function updatedFrom(): void
    {
        $this->period = 'custom';
        $this->rememberFilters();
    }

    public function updatedUntil(): void
    {
        $this->period = 'custom';
        $this->rememberFilters();
    }

    public function updatedDevice(): void
    {
        $this->rememberFilters();
    }

    public function updatedPlace(): void
    {
        $this->rememberFilters();
    }

    public function resetFilters(): void
    {
        $this->period = '30';
        $this->from = '';
        $this->until = '';
        $this->device = 'all';
        $this->place = 'all';
        $this->rememberFilters();
    }

    /**
     * @return array{period: string, from: string, until: string, device: string, place: string}
     */
    public function trafficFilters(): array
    {
        return [
            'period' => $this->period,
            'from' => $this->from,
            'until' => $this->until,
            'device' => $this->device,
            'place' => $this->place,
        ];
    }

    /**
     * @return array<int, string>
     */
    public function placeOptions(): array
    {
        return SiteVisitor::query()->pluck('area')
            ->merge(SiteVisitor::query()->pluck('city'))
            ->merge(Order::query()->pluck('city_name'))
            ->filter(fn ($place) => filled($place))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function boardData(): array
    {
        return [
            'report' => app(TrafficReport::class)->summary($this->trafficFilters()),
            'placeOptions' => $this->placeOptions(),
            'period' => $this->period,
            'device' => $this->device,
            'place' => $this->place,
        ];
    }

    private function allowedPeriod(string $period): string
    {
        return in_array($period, [...AnalyticsPeriod::OPTIONS, 'custom'], true) ? $period : '30';
    }

    private function rememberFilters(): void
    {
        session(['traffic.filters' => $this->trafficFilters()]);
    }
}
