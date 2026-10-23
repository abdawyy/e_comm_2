<?php

namespace App\Listeners;

use App\Actions\GenerateInvoiceAction;
use App\Actions\SendOrderNotifications;
use App\Events\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleOrderPaid implements ShouldQueue
{
    public function __construct(
        private GenerateInvoiceAction $invoices,
        private SendOrderNotifications $notifications,
    ) {}

    public function handle(OrderPaid $event): void
    {
        $order = $event->order->fresh();

        if (! $order || $order->payment_status !== 'paid') {
            return;
        }

        if (! $order->invoice_path) {
            $this->invoices->execute($order);
            $order->refresh();
        }

        $this->notifications->send($order);
    }
}
