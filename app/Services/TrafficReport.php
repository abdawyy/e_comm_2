<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use Illuminate\Support\Carbon;

class TrafficReport
{
    /**
     * @param  array{period?: string, from?: string, until?: string, device?: string, place?: string}  $filters
     * @return array<string, mixed>
     */
    public function summary(array $filters = []): array
    {
        $period = new AnalyticsPeriod;
        $tz = $period->timezone();
        [$start, $end] = $this->range($filters, $tz);
        $device = $this->device($filters['device'] ?? 'all');
        $place = trim((string) ($filters['place'] ?? 'all'));
        $place = $place === '' ? 'all' : $place;

        $visitors = SiteVisitor::query()
            ->whereBetween('last_seen_at', [$start, $end])
            ->when($device !== 'all', fn ($query) => $query->where('device_type', $device))
            ->when($place !== 'all', fn ($query) => $query->where(function ($inner) use ($place) {
                $inner->where('area', $place)->orWhere('city', $place)->orWhere('region', $place);
            }))
            ->get();

        $visitorIds = $visitors->pluck('id');
        $sessionIds = $visitors->pluck('session_id')->filter();
        $narrow = $device !== 'all' || $place !== 'all';

        $views = SitePageView::query()
            ->whereBetween('viewed_at', [$start, $end])
            ->when($narrow, fn ($query) => $query->whereIn('site_visitor_id', $visitorIds->isEmpty() ? [0] : $visitorIds))
            ->get();
        $productViews = ProductView::query()
            ->whereBetween('visited_at', [$start, $end])
            ->when($narrow, fn ($query) => $query->whereIn('session_id', $sessionIds->isEmpty() ? ['__none__'] : $sessionIds))
            ->get();

        $orders = Order::query()
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end])
            ->when($place !== 'all', fn ($query) => $query->where(function ($inner) use ($place) {
                $inner->where('city_name', $place)->orWhere('address', 'like', '%'.$place.'%');
            }))
            ->when($device !== 'all', function ($query) use ($visitors) {
                $userIds = $visitors->pluck('user_id')->filter()->unique();
                $query->whereIn('user_id', $userIds->isEmpty() ? [0] : $userIds);
            })
            ->get();

        $sessions = $visitors->count();
        $mobile = $visitors->where('device_type', 'mobile')->count();
        $series = $this->series($views, $productViews, $orders, $start, $end, $tz);

        return [
            'active' => SiteVisitor::query()
                ->where('last_seen_at', '>=', now()->subMinutes(5))
                ->when($device !== 'all', fn ($query) => $query->where('device_type', $device))
                ->when($place !== 'all', fn ($query) => $query->where(function ($inner) use ($place) {
                    $inner->where('area', $place)->orWhere('city', $place)->orWhere('region', $place);
                }))
                ->count(),
            'sessions' => $sessions,
            'page_views' => $views->count(),
            'orders' => $orders->count(),
            'conversion' => $sessions > 0 ? round(($orders->count() / $sessions) * 100, 1) : 0,
            'mobile_share' => $sessions > 0 ? round(($mobile / $sessions) * 100, 1) : 0,
            'series' => $series,
            'bucket' => $series['bucket'],
            'by_hour' => $this->bars($this->byHour($views, $productViews, $orders, $tz)),
            'by_weekday' => $this->bars($this->byWeekday($views, $productViews, $orders, $tz)),
            'cities' => $this->groupCount($visitors, 'city'),
            'places' => $this->groupCount($visitors->filter(fn ($visitor) => filled($visitor->area)), 'area'),
            'regions' => $this->groupCount($visitors, 'region'),
            'countries' => $this->groupCount($visitors, 'country'),
            'devices' => $this->groupCount($visitors, 'device_type'),
            'referrers' => $this->referrers($visitors),
            'paths' => $views->groupBy('path')->map->count()->sortDesc()->take(8)->all(),
            'range_label' => $start->copy()->timezone($tz)->format('j M Y').' – '.$end->copy()->timezone($tz)->format('j M Y'),
        ];
    }

    /**
     * @param  array{period?: string, from?: string, until?: string}  $filters
     * @return array{0: Carbon, 1: Carbon}
     */
    private function range(array $filters, string $tz): array
    {
        $now = now($tz);
        $period = (string) ($filters['period'] ?? '30');

        if ($period === 'custom' && filled($filters['from'] ?? null) && filled($filters['until'] ?? null)) {
            $start = Carbon::parse((string) $filters['from'], $tz)->startOfDay();
            $end = Carbon::parse((string) $filters['until'], $tz)->endOfDay();

            if ($end->lt($start)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            }

            if ($start->diffInDays($end) > 366) {
                $start = $end->copy()->subDays(366)->startOfDay();
            }

            return [$start->utc(), $end->utc()];
        }

        if ($period === 'today') {
            return [$now->copy()->startOfDay()->utc(), $now->copy()->utc()];
        }

        $days = in_array($period, AnalyticsPeriod::OPTIONS, true) ? (int) $period : 30;

        return [
            $now->copy()->subDays(max($days, 1) - 1)->startOfDay()->utc(),
            $now->copy()->endOfDay()->utc(),
        ];
    }

    private function device(string $device): string
    {
        return in_array($device, ['desktop', 'mobile', 'tablet'], true) ? $device : 'all';
    }

    /**
     * @return array{bucket: string, rows: array<int, array{label: string, pages: int, products: int, orders: int, pages_pct: int, products_pct: int, orders_pct: int}>}
     */
    private function series($views, $productViews, $orders, Carbon $start, Carbon $end, string $tz): array
    {
        $startLocal = $start->copy()->timezone($tz);
        $endLocal = $end->copy()->timezone($tz);
        $days = $startLocal->copy()->startOfDay()->diffInDays($endLocal->copy()->startOfDay()) + 1;
        $bucket = $days <= 2 ? 'hour' : ($days <= 62 ? 'day' : ($days <= 200 ? 'week' : 'month'));
        $rows = [];
        $cursor = $startLocal->copy();

        if ($bucket === 'hour') {
            $cursor->startOfHour();
            while ($cursor <= $endLocal && count($rows) < 48) {
                $rows[$cursor->format('Y-m-d H')] = $this->blank($cursor->format('H:00'));
                $cursor->addHour();
            }
        } elseif ($bucket === 'day') {
            $cursor->startOfDay();
            while ($cursor <= $endLocal) {
                $rows[$cursor->toDateString()] = $this->blank($cursor->format('j M'));
                $cursor->addDay();
            }
        } elseif ($bucket === 'week') {
            $cursor->startOfWeek();
            while ($cursor <= $endLocal) {
                $rows[$cursor->format('o-W')] = $this->blank($cursor->format('j M'));
                $cursor->addWeek();
            }
        } else {
            $cursor->startOfMonth();
            while ($cursor <= $endLocal) {
                $rows[$cursor->format('Y-m')] = $this->blank($cursor->format('M Y'));
                $cursor->addMonth();
            }
        }

        $keyFor = function (Carbon $moment) use ($bucket, $tz): string {
            $local = $moment->copy()->timezone($tz);

            return match ($bucket) {
                'hour' => $local->format('Y-m-d H'),
                'week' => $local->startOfWeek()->format('o-W'),
                'month' => $local->format('Y-m'),
                default => $local->toDateString(),
            };
        };

        foreach ($views as $view) {
            $key = $keyFor($view->viewed_at);
            if (isset($rows[$key])) {
                $rows[$key]['pages']++;
            }
        }

        foreach ($productViews as $view) {
            $key = $keyFor($view->visited_at);
            if (isset($rows[$key])) {
                $rows[$key]['products']++;
            }
        }

        foreach ($orders as $order) {
            $key = $keyFor($order->created_at);
            if (isset($rows[$key])) {
                $rows[$key]['orders']++;
            }
        }

        return [
            'bucket' => $bucket,
            'rows' => $this->bars(array_values($rows)),
        ];
    }

    /**
     * @return array{label: string, pages: int, products: int, orders: int}
     */
    private function blank(string $label): array
    {
        return ['label' => $label, 'pages' => 0, 'products' => 0, 'orders' => 0];
    }

    /**
     * @param  array<int, array{label: string, pages: int, products: int, orders: int}>  $rows
     * @return array<int, array{label: string, pages: int, products: int, orders: int, pages_pct: int, products_pct: int, orders_pct: int}>
     */
    private function bars(array $rows): array
    {
        $max = 1;

        foreach ($rows as $row) {
            $max = max($max, $row['pages'], $row['products'], $row['orders']);
        }

        return array_map(function (array $row) use ($max) {
            $row['pages_pct'] = (int) round($row['pages'] / $max * 100);
            $row['products_pct'] = (int) round($row['products'] / $max * 100);
            $row['orders_pct'] = (int) round($row['orders'] / $max * 100);

            return $row;
        }, $rows);
    }

    /**
     * @return array<int, array{label: string, pages: int, products: int, orders: int}>
     */
    private function byHour($views, $productViews, $orders, string $tz): array
    {
        $rows = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $rows[$hour] = $this->blank(str_pad((string) $hour, 2, '0', STR_PAD_LEFT));
        }

        foreach ($views as $view) {
            $rows[(int) $view->viewed_at->copy()->timezone($tz)->format('G')]['pages']++;
        }

        foreach ($productViews as $view) {
            $rows[(int) $view->visited_at->copy()->timezone($tz)->format('G')]['products']++;
        }

        foreach ($orders as $order) {
            $rows[(int) $order->created_at->copy()->timezone($tz)->format('G')]['orders']++;
        }

        return array_values($rows);
    }

    /**
     * @return array<int, array{label: string, pages: int, products: int, orders: int}>
     */
    private function byWeekday($views, $productViews, $orders, string $tz): array
    {
        $rows = [];

        for ($day = 0; $day < 7; $day++) {
            $rows[$day] = $this->blank(Carbon::now()->locale(app()->getLocale())->startOfWeek(Carbon::SUNDAY)->addDays($day)->isoFormat('ddd'));
        }

        foreach ($views as $view) {
            $rows[(int) $view->viewed_at->copy()->timezone($tz)->dayOfWeek]['pages']++;
        }

        foreach ($productViews as $view) {
            $rows[(int) $view->visited_at->copy()->timezone($tz)->dayOfWeek]['products']++;
        }

        foreach ($orders as $order) {
            $rows[(int) $order->created_at->copy()->timezone($tz)->dayOfWeek]['orders']++;
        }

        return array_values($rows);
    }

    /**
     * @return array<string, int>
     */
    private function groupCount($visitors, string $field): array
    {
        return $visitors
            ->groupBy(fn ($visitor) => $visitor->{$field} ?: '—')
            ->map->count()
            ->sortDesc()
            ->take(8)
            ->all();
    }

    /**
     * @return array<string, int>
     */
    private function referrers($visitors): array
    {
        return $visitors
            ->groupBy(fn ($visitor) => $visitor->referrer_host ?: 'direct')
            ->map->count()
            ->sortDesc()
            ->take(8)
            ->all();
    }
}
