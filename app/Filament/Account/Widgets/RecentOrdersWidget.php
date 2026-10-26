<?php

namespace App\Filament\Account\Widgets;

use App\Filament\Account\Resources\OrderResource;
use App\Models\Order;
use Filament\Widgets\Widget;

class RecentOrdersWidget extends Widget
{
    protected string $view = 'filament.account.widgets.recent-orders';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'orders' => Order::query()->where('user_id', auth()->id())->latest()->limit(8)->get(),
            'indexUrl' => OrderResource::getUrl('index'),
            'orderUrl' => fn (Order $order) => OrderResource::getUrl('view', ['record' => $order]),
        ];
    }
}
