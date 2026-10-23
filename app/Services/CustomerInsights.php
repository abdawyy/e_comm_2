<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Models\User;
use App\Support\Places;
use Illuminate\Support\Carbon;

class CustomerInsights
{
    /** @var array<int, array<string, mixed>> */
    private array $cache = [];

    public function __construct(
        private DecimalMath $math,
        private Places $places,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function for(User $user): array
    {
        return $this->cache[$user->id] ??= $this->build($user);
    }

    /**
     * @return array<string, mixed>
     */
    private function build(User $user): array
    {
        $tz = (string) config('store.analytics_timezone', 'Africa/Cairo');
        $visitors = SiteVisitor::query()->where('user_id', $user->id)->orderByDesc('last_seen_at')->get();
        $sessionIds = $visitors->pluck('session_id')->filter()->all();
        $pageViews = SitePageView::query()
            ->whereIn('site_visitor_id', $visitors->pluck('id'))
            ->get();
        $productViews = ProductView::query()
            ->with('product')
            ->where(function ($query) use ($user, $sessionIds) {
                $query->where('user_id', $user->id);

                if ($sessionIds !== []) {
                    $query->orWhereIn('session_id', $sessionIds);
                }
            })
            ->get();
        $orders = Order::query()->where('user_id', $user->id)->orderBy('created_at')->get();
        $counted = $orders->where('status', '!=', 'cancelled')->values();
        $spent = $this->math->zero();

        foreach ($counted as $order) {
            $spent = $this->math->add($spent, (string) $order->total);
        }

        $items = OrderItem::query()
            ->whereIn('order_id', $counted->pluck('id'))
            ->where('kind', 'product')
            ->get();

        $locations = collect($visitors->map(fn (SiteVisitor $visitor) => $visitor->locationLabel())->all())
            ->merge($orders->map(fn (Order $order) => $this->places->match($order->address, $order->city_name) ?: $order->city_name)->all())
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(5)
            ->values()
            ->all();

        return [
            'locations' => $locations,
            'devices' => $visitors->groupBy(fn ($visitor) => $visitor->device_type ?: 'desktop')->map->count()->sortDesc()->all(),
            'sessions' => $visitors->count(),
            'first_seen' => $visitors->min('first_seen_at'),
            'last_seen' => $visitors->max('last_seen_at'),
            'last_location' => $visitors->first()?->locationLabel() ?: '',
            'last_visit' => $visitors->first()?->last_seen_at,
            'usual_browse_hour' => $this->modeHour($pageViews->pluck('viewed_at'), $tz),
            'usual_purchase_hour' => $this->modeHour($counted->pluck('created_at'), $tz),
            'most_viewed' => $this->mostViewed($productViews),
            'most_purchased' => $this->mostPurchased($items),
            'recent' => $orders->sortByDesc('created_at')->take(5)->values(),
            'orders_count' => $orders->count(),
            'total_spent' => $spent,
            'average_order' => $counted->count() > 0 ? $this->math->div($spent, (string) $counted->count()) : $this->math->zero(),
            'first_order' => $orders->first()?->created_at,
            'last_order' => $orders->sortByDesc('created_at')->first()?->created_at,
            'cancelled_count' => $orders->where('status', 'cancelled')->count(),
            'activity' => $this->activity($pageViews, $orders, $tz),
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, mixed>  $moments
     */
    private function modeHour($moments, string $tz): ?int
    {
        $counts = [];

        foreach ($moments as $moment) {
            if (! $moment) {
                continue;
            }

            $hour = (int) Carbon::parse($moment)->timezone($tz)->format('G');
            $counts[$hour] = ($counts[$hour] ?? 0) + 1;
        }

        if ($counts === []) {
            return null;
        }

        arsort($counts);

        return (int) array_key_first($counts);
    }

    /**
     * @return array<int, array{name: string, views: int}>
     */
    private function mostViewed($views): array
    {
        return $views->groupBy('product_id')->map(function ($group) {
            $product = $group->first()->product;

            return [
                'name' => $product ? (string) $product->name : '#'.$group->first()->product_id,
                'views' => $group->count(),
            ];
        })->sortByDesc('views')->take(5)->values()->all();
    }

    /**
     * @return array<int, array{name: string, quantity: string}>
     */
    private function mostPurchased($items): array
    {
        $rows = [];

        foreach ($items as $item) {
            $name = $item->localizedName();
            $rows[$name]['name'] = $name;
            $rows[$name]['quantity'] = $this->math->add($rows[$name]['quantity'] ?? '0', (string) $item->quantity);
        }

        uasort($rows, fn ($a, $b) => $this->math->comp($b['quantity'], $a['quantity']));

        return array_slice(array_values($rows), 0, 5);
    }

    /**
     * @return array<int, array{label: string, views: int, orders: int}>
     */
    private function activity($pageViews, $orders, string $tz): array
    {
        $rows = [];

        foreach ($pageViews as $view) {
            $label = Carbon::parse($view->viewed_at)->timezone($tz)->toDateString();
            $rows[$label]['label'] = $label;
            $rows[$label]['views'] = ($rows[$label]['views'] ?? 0) + 1;
            $rows[$label]['orders'] = $rows[$label]['orders'] ?? 0;
        }

        foreach ($orders as $order) {
            $label = $order->created_at->copy()->timezone($tz)->toDateString();
            $rows[$label]['label'] = $label;
            $rows[$label]['views'] = $rows[$label]['views'] ?? 0;
            $rows[$label]['orders'] = ($rows[$label]['orders'] ?? 0) + 1;
        }

        ksort($rows);

        return array_values($rows);
    }
}
