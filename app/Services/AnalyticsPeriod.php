<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class AnalyticsPeriod
{
    public const OPTIONS = ['today', '7', '30', '90', '365'];

    public function key(): string
    {
        $key = (string) session('analytics.period', '30');

        return in_array($key, self::OPTIONS, true) ? $key : '30';
    }

    public function set(string $key): void
    {
        if (in_array($key, self::OPTIONS, true)) {
            session(['analytics.period' => $key]);
        }
    }

    public function timezone(): string
    {
        return (string) config('store.analytics_timezone', 'Africa/Cairo');
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: Carbon, 3: Carbon}
     */
    public function windows(): array
    {
        $now = now($this->timezone());
        $key = $this->key();

        if ($key === 'today') {
            $start = $now->copy()->startOfDay();
            $end = $now->copy();
            $previousEnd = $end->copy()->subDay();
            $previousStart = $previousEnd->copy()->startOfDay();

            return [$start->utc(), $end->utc(), $previousStart->utc(), $previousEnd->utc()];
        }

        $days = (int) $key;
        $start = $now->copy()->subDays($days - 1)->startOfDay();
        $end = $now->copy()->endOfDay();
        $previousEnd = $start->copy()->subSecond();
        $previousStart = $start->copy()->subDays($days)->startOfDay();

        return [$start->utc(), $end->utc(), $previousStart->utc(), $previousEnd->utc()];
    }

    public function bucket(): string
    {
        return match ($this->key()) {
            'today' => 'hour',
            '365' => 'month',
            default => 'day',
        };
    }

    public function label(CarbonInterface $moment): string
    {
        $local = $moment->copy()->timezone($this->timezone());

        return match ($this->bucket()) {
            'hour' => $local->format('H:00'),
            'month' => $local->format('Y-m'),
            default => $local->format('Y-m-d'),
        };
    }
}
