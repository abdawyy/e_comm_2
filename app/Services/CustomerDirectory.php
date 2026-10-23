<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteVisitor;
use App\Models\User;
use App\Support\Places;

class CustomerDirectory
{
    public function __construct(
        private CustomerInsights $insights,
        private DecimalMath $math,
        private Places $places,
    ) {}

    /**
     * @return array{places: array<int, array{name: string, visits: int, orders: int}>, customers: array<int, array<string, mixed>>, guests: array<int, array<string, mixed>>}
     */
    public function report(): array
    {
        $customers = User::query()
            ->with(['orders' => fn ($query) => $query->latest()])
            ->where(function ($query) {
                $query->where('is_admin', false)->orWhereHas('orders');
            })
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => $this->customer($user))
            ->all();

        $guests = Order::query()
            ->whereNull('user_id')
            ->latest()
            ->get()
            ->groupBy(fn (Order $order) => $order->customer_phone ?: $order->customer_email ?: 'guest')
            ->map(fn ($orders, $key) => [
                'name' => (string) ($orders->first()->customer_name ?: $key),
                'phone' => (string) ($orders->first()->customer_phone ?: ''),
                'orders' => $orders->map(fn (Order $order) => $this->orderRow($order))->all(),
            ])
            ->values()
            ->all();

        return [
            'places' => $this->places($customers, $guests),
            'visitors' => $this->visitors(),
            'customers' => $customers,
            'guests' => $guests,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function visitors(): array
    {
        return SiteVisitor::query()
            ->with('user')
            ->latest('last_seen_at')
            ->limit(30)
            ->get()
            ->map(fn (SiteVisitor $visitor) => [
                'place' => $visitor->area ?: $visitor->city,
                'location' => $visitor->locationLabel(),
                'device' => $visitor->device_type,
                'name' => $visitor->user?->name,
                'last_seen_at' => $visitor->last_seen_at,
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function customer(User $user): array
    {
        $insights = $this->insights->for($user);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'orders_count' => $insights['orders_count'],
            'spent' => $this->math->format($insights['total_spent']),
            'average' => $this->math->format($insights['average_order']),
            'locations' => $insights['locations'],
            'devices' => $insights['devices'],
            'sessions' => $insights['sessions'],
            'browse_hour' => $insights['usual_browse_hour'],
            'purchase_hour' => $insights['usual_purchase_hour'],
            'viewed' => $insights['most_viewed'],
            'purchased' => $insights['most_purchased'],
            'last_visit' => $insights['last_visit'],
            'orders' => $user->orders->map(fn (Order $order) => $this->orderRow($order))->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function orderRow(Order $order): array
    {
        $place = $this->places->match($order->address, $order->city_name) ?: $order->city_name;

        return [
            'id' => $order->id,
            'reference' => $order->reference,
            'status' => $order->status,
            'total' => $this->math->format((string) $order->total),
            'city' => $order->city_name,
            'address' => $order->address,
            'place' => $place,
            'created_at' => $order->created_at,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $customers
     * @param  array<int, array<string, mixed>>  $guests
     * @return array<int, array{name: string, visits: int, orders: int}>
     */
    private function places(array $customers, array $guests): array
    {
        $rows = [];

        foreach (SiteVisitor::query()->get(['area', 'city', 'region']) as $visitor) {
            $name = $visitor->area ?: $visitor->city ?: $visitor->region;

            if (blank($name)) {
                continue;
            }

            $rows[$name]['name'] = $name;
            $rows[$name]['visits'] = ($rows[$name]['visits'] ?? 0) + 1;
            $rows[$name]['orders'] = $rows[$name]['orders'] ?? 0;
        }

        $orders = collect($customers)->flatMap(fn ($customer) => $customer['orders'])
            ->merge(collect($guests)->flatMap(fn ($guest) => $guest['orders']));

        foreach ($orders as $order) {
            $name = $order['place'];

            if (blank($name)) {
                continue;
            }

            $rows[$name]['name'] = $name;
            $rows[$name]['visits'] = $rows[$name]['visits'] ?? 0;
            $rows[$name]['orders'] = ($rows[$name]['orders'] ?? 0) + 1;
        }

        uasort($rows, fn ($left, $right) => ($right['visits'] + $right['orders']) <=> ($left['visits'] + $left['orders']));

        return array_values($rows);
    }
}
