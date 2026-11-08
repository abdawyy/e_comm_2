<?php

namespace App\Listeners;

use App\Actions\SendOrderNotifications;
use App\Events\OrderStatusChanged;
use App\Mail\OrderStatusChangedMail;

class HandleOrderStatusChanged
{
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order->fresh();

        if (! $order) {
            return;
        }

        app(SendOrderNotifications::class)->sendCopy(
            $order,
            fn () => new OrderStatusChangedMail($order, $event->from, $event->to),
        );
    }
}
