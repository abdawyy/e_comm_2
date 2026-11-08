<?php

namespace App\Listeners;

use App\Actions\SendOrderNotifications;
use App\Events\OrderPaid;

class HandleOrderPaid
{
    public function __construct(
        private SendOrderNotifications $notifications,
    ) {}

    public function handle(OrderPaid $event): void
    {
        $order = $event->order->fresh();

        if (! $order || $order->payment_status !== 'paid') {
            return;
        }

        $this->notifications->send($order);
    }
}
