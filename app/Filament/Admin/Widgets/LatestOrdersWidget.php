<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\OrderResource;
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
        return [
            'orders' => app(SalesReport::class)->summary()['latest'],
            'orderUrl' => fn ($order) => OrderResource::getUrl('view', ['record' => $order]),
        ];
    }
}
