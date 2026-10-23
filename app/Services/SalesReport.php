<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductView;
use Illuminate\Support\Collection;

class SalesReport
{
    /** @var array<string, mixed> */
    private array $memo = [];

    public function __construct(
        private AnalyticsPeriod $period,
        private DecimalMath $math,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        return $this->memo['summary'] ??= $this->buildSummary();
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSummary(): array
    {
        [$start, $end, $previousStart, $previousEnd] = $this->period->windows();
        $current = $this->ordersBetween($start, $end);
        $previous = $this->ordersBetween($previousStart, $previousEnd);
        $revenueOrders = $current->where('status', '!=', 'cancelled');
        $previousRevenue = $previous->where('status', '!=', 'cancelled');

        $revenue = $this->sum($revenueOrders);
        $previousRevenueTotal = $this->sum($previousRevenue);
        $count = $revenueOrders->count();
        $previousCount = $previousRevenue->count();
        $pending = $current->where('status', 'pending')->count();
        $previousPending = $previous->where('status', 'pending')->count();
        $average = $count > 0 ? $this->math->div($revenue, (string) $count) : $this->math->zero();
        $previousAverage = $previousCount > 0 ? $this->math->div($previousRevenueTotal, (string) $previousCount) : $this->math->zero();

        return [
            'revenue' => $revenue,
            'revenue_change' => $this->change($revenue, $previousRevenueTotal),
            'orders' => $count,
            'orders_change' => $this->change((string) $count, (string) $previousCount),
            'average' => $average,
            'average_change' => $this->change($average, $previousAverage),
            'pending' => $pending,
            'pending_change' => $this->change((string) $pending, (string) $previousPending),
            'series' => $this->series($revenueOrders),
            'statuses' => $current->groupBy('status')->map->count()->all(),
            'cities' => $this->cities($revenueOrders),
            'products' => $this->topProducts($revenueOrders->pluck('id')->all()),
            'views' => $this->topViews($start, $end),
            'latest' => Order::query()->latest('id')->limit(8)->get(),
            'period' => $this->period->key(),
        ];
    }

    /**
     * @return Collection<int, Order>
     */
    private function ordersBetween($start, $end): Collection
    {
        return Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->get(['id', 'total', 'status', 'city_name', 'created_at', 'reference', 'customer_name', 'payment_status']);
    }

    /**
     * @param  Collection<int, Order>  $orders
     */
    private function sum(Collection $orders): string
    {
        $total = $this->math->zero();

        foreach ($orders as $order) {
            $total = $this->math->add($total, (string) $order->total);
        }

        return $total;
    }

    private function change(string $current, string $previous): ?string
    {
        if ($this->math->comp($previous, '0') === 0) {
            return null;
        }

        $delta = $this->math->sub($current, $previous);
        $ratio = bcdiv($delta, $this->math->normalize($previous), $this->math->scale() + 6);

        return $this->math->roundHalfAwayFromZero(bcmul($ratio, '100', $this->math->scale() + 4), 1);
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return array<int, array{label: string, revenue: string, orders: int}>
     */
    private function series(Collection $orders): array
    {
        $buckets = [];

        foreach ($orders as $order) {
            $label = $this->period->label($order->created_at);
            $buckets[$label]['revenue'] = $this->math->add($buckets[$label]['revenue'] ?? '0', (string) $order->total);
            $buckets[$label]['orders'] = ($buckets[$label]['orders'] ?? 0) + 1;
            $buckets[$label]['label'] = $label;
        }

        ksort($buckets);

        return array_values($buckets);
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return array<int, array{city: string, orders: int, revenue: string}>
     */
    private function cities(Collection $orders): array
    {
        $rows = [];

        foreach ($orders as $order) {
            $city = $order->city_name ?: '—';
            $rows[$city]['city'] = $city;
            $rows[$city]['orders'] = ($rows[$city]['orders'] ?? 0) + 1;
            $rows[$city]['revenue'] = $this->math->add($rows[$city]['revenue'] ?? '0', (string) $order->total);
        }

        usort($rows, fn ($a, $b) => $this->math->comp($b['revenue'], $a['revenue']));

        return array_slice(array_values($rows), 0, 10);
    }

    /**
     * @param  array<int, int>  $orderIds
     * @return array<int, array{name: string, revenue: string}>
     */
    private function topProducts(array $orderIds): array
    {
        if ($orderIds === []) {
            return [];
        }

        $items = OrderItem::query()->whereIn('order_id', $orderIds)->where('kind', 'product')->get();
        $rows = [];

        foreach ($items as $item) {
            $name = $item->localizedName();
            $rows[$name]['name'] = $name;
            $rows[$name]['revenue'] = $this->math->add($rows[$name]['revenue'] ?? '0', (string) $item->line_subtotal);
        }

        usort($rows, fn ($a, $b) => $this->math->comp($b['revenue'], $a['revenue']));

        return array_slice(array_values($rows), 0, 8);
    }

    /**
     * @return array<int, array{name: string, views: int}>
     */
    private function topViews($start, $end): array
    {
        return ProductView::query()
            ->with('product')
            ->whereBetween('visited_at', [$start, $end])
            ->get()
            ->groupBy('product_id')
            ->map(function ($views) {
                $product = $views->first()->product;

                return [
                    'name' => $product ? (string) $product->name : '#'.$views->first()->product_id,
                    'views' => $views->count(),
                ];
            })
            ->sortByDesc('views')
            ->take(8)
            ->values()
            ->all();
    }
}
