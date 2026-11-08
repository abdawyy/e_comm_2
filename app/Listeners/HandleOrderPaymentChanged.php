<?php

namespace App\Listeners;

use App\Actions\SendOrderNotifications;
use App\Events\OrderPaymentChanged;
use App\Mail\OrderPaymentChangedMail;

class HandleOrderPaymentChanged
{
    public function handle(OrderPaymentChanged $event): void
    {
        $order = $event->order->fresh();

        if (! $order) {
            return;
        }

        if ($event->to === 'paid' && $order->confirmation_sent_at === null) {
            return;
        }

        app(SendOrderNotifications::class)->sendCopy(
            $order,
            fn () => new OrderPaymentChangedMail($order, $event->from, $event->to),
        );
    }
}
