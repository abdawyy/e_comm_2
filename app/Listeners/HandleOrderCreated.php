<?php

namespace App\Listeners;

use App\Actions\GenerateInvoiceAction;
use App\Actions\SendOrderNotifications;
use App\Events\OrderCreated;

class HandleOrderCreated
{
    public function __construct(
        private GenerateInvoiceAction $invoices,
        private SendOrderNotifications $notifications,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $order = $event->order->fresh();

        if (! $order) {
            return;
        }

        if (! $order->invoice_path) {
            $this->invoices->execute($order);
        }

        if ($order->payment_gateway === 'cod') {
            $this->notifications->send($order->fresh());

            return;
        }

        $this->notifications->notifyAdmin($order->fresh());
    }
}
