<?php

namespace App\Listeners;

use App\Actions\GenerateInvoiceAction;
use App\Actions\SendOrderNotifications;
use App\Events\OrderCreated;
use App\Models\Order;

class HandleOrderCreated
{
    public function __construct(
        private GenerateInvoiceAction $invoices,
        private SendOrderNotifications $notifications,
    ) {}

    public function handle(OrderCreated $event): void
    {
        if (! app()->runningUnitTests()) {
            return;
        }

        $order = Order::query()->find($event->order->getKey());

        if ($order) {
            $this->deliver($order);
        }
    }

    public function deliver(Order $order): void
    {
        if (! $order->invoice_path) {
            $this->invoices->execute($order);
            $order = $order->fresh() ?? $order;
        }

        if ($order->payment_gateway === 'cod') {
            $this->notifications->send($order->fresh() ?? $order);

            return;
        }

        $this->notifications->notifyAdmin($order->fresh() ?? $order);
        $order->forceFill([
            'mail_status' => filled($order->customer_email) ? 'sent' : 'failed',
            'mail_error' => filled($order->customer_email) ? null : 'Customer has no email',
        ])->save();
    }
}
