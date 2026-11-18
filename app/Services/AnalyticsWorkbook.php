<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShippingCarrier;

class AnalyticsWorkbook
{
    public function __construct(
        private SalesReport $sales,
        private TrafficReport $traffic,
        private CustomerDirectory $customers,
        private DecimalMath $math,
    ) {}

    /**
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: string}>}>
     */
    public function sections(string $report): array
    {
        $reports = $report === 'all'
            ? ['sales', 'traffic', 'customers', 'delivery']
            : [$report];

        $sections = [];

        foreach ($reports as $name) {
            $sections = array_merge($sections, $this->one($name));
        }

        return $sections;
    }

    /**
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: string}>}>
     */
    private function one(string $report): array
    {
        return match ($report) {
            'sales' => $this->sales(),
            'traffic' => $this->traffic(),
            'customers' => $this->customers(),
            'delivery' => $this->delivery(),
            default => [],
        };
    }

    /**
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: string}>}>
     */
    private function sales(): array
    {
        $summary = $this->sales->summary();
        $rows = [
            $this->line('Revenue', $this->math->format((string) $summary['revenue'])),
            $this->line('Orders', (string) $summary['orders']),
            $this->line('Average', $this->math->format((string) $summary['average'])),
            $this->line('Pending', (string) $summary['pending']),
        ];

        foreach ($summary['series'] as $point) {
            $rows[] = $this->line('Day '.$point['label'], $point['orders'].' / '.$this->math->format((string) $point['revenue']));
        }

        foreach ($summary['products'] as $product) {
            $rows[] = $this->line('Product '.$product['name'], $this->math->format((string) $product['revenue']));
        }

        foreach ($summary['cities'] as $city) {
            $rows[] = $this->line('Place '.$city['location'], $city['orders'].' / '.$this->math->format((string) $city['revenue']));
        }

        foreach ($summary['payments'] as $gateway => $count) {
            $rows[] = $this->line('Payment '.$gateway, (string) $count);
        }

        foreach ($summary['coupons'] as $coupon) {
            $rows[] = $this->line('Coupon '.$coupon['code'], $coupon['orders'].' / '.$this->math->format((string) $coupon['revenue']));
        }

        return [['title' => 'Sales', 'rows' => $rows]];
    }

    /**
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: string}>}>
     */
    private function traffic(): array
    {
        $filters = session('traffic.filters', []);
        $summary = $this->traffic->summary(is_array($filters) ? $filters : []);
        $rows = [
            $this->line('Range', (string) ($summary['range_label'] ?? '')),
            $this->line('Active now', (string) $summary['active']),
            $this->line('Sessions', (string) $summary['sessions']),
            $this->line('Page views', (string) $summary['page_views']),
            $this->line('Orders', (string) $summary['orders']),
            $this->line('Conversion', $summary['conversion'].'%'),
            $this->line('Mobile share', $summary['mobile_share'].'%'),
        ];

        foreach ($summary['devices'] as $label => $count) {
            $rows[] = $this->line('Device '.$label, (string) $count);
        }

        foreach ($summary['places'] as $label => $count) {
            $rows[] = $this->line('Place '.$label, (string) $count);
        }

        foreach ($summary['paths'] as $path => $count) {
            $rows[] = $this->line('Page '.$path, (string) $count);
        }

        foreach ($summary['latest'] as $visit) {
            $rows[] = $this->line('Visit '.$visit['seen'], trim($visit['location'].' '.$visit['device']));
        }

        return [['title' => 'Traffic', 'rows' => $rows]];
    }

    /**
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: string}>}>
     */
    private function customers(): array
    {
        $report = $this->customers->report();
        $rows = [];

        foreach ($report['customers'] as $customer) {
            $rows[] = $this->line(trim((string) $customer['name'].' '.(string) ($customer['phone'] ?? '')), (string) count($customer['orders']).' orders');
        }

        foreach ($report['guests'] as $guest) {
            $rows[] = $this->line('Guest '.trim((string) $guest['name'].' '.(string) ($guest['phone'] ?? '')), (string) count($guest['orders']).' orders');
        }

        foreach ($report['visitors'] as $visitor) {
            $rows[] = $this->line('Visitor '.($visitor['name'] ?: $visitor['location']), (string) ($visitor['last_seen_at'] ?? ''));
        }

        return [['title' => 'Customers', 'rows' => $rows === [] ? [$this->line('Customers', '0')] : $rows]];
    }

    /**
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: string}>}>
     */
    private function delivery(): array
    {
        $rows = [
            $this->line('Unassigned', (string) Order::query()->whereNull('shipping_carrier_id')->count()),
        ];

        foreach (ShippingCarrier::query()->orderBy('sort_order')->get() as $carrier) {
            $count = Order::query()->where('shipping_carrier_id', $carrier->id)->count();
            $rows[] = $this->line((string) $carrier->name, (string) $count);
        }

        foreach (Order::query()->latest('id')->limit(100)->get(['reference', 'customer_name', 'city_name', 'address', 'shipping_carrier_id']) as $order) {
            $rows[] = $this->line($order->reference.' '.$order->customer_name, trim($order->city_name.' '.$order->address));
        }

        return [['title' => 'Delivery', 'rows' => $rows]];
    }

    /**
     * @return array{label: string, value: string}
     */
    private function line(string $label, string $value): array
    {
        return ['label' => $label, 'value' => $value];
    }
}
