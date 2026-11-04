<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\OrderResource;
use App\Services\DecimalMath;
use App\Services\SalesReport;
use Filament\Widgets\Widget;

class LatestOrdersWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.latest-orders';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $orders = app(SalesReport::class)->summary()['latest'];
        $math = app(DecimalMath::class);
        $max = '0';

        foreach ($orders as $order) {
            if ($math->comp((string) $order->total, $max) > 0) {
                $max = (string) $order->total;
            }
        }

        return [
            'orders' => $orders,
            'max' => $max,
            'math' => $math,
            'orderUrl' => fn ($order) => OrderResource::getUrl('view', ['record' => $order]),
        ];
    }
}
