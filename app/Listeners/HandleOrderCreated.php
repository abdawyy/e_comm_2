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
        $orderId = $event->order->getKey();

        if (app()->runningUnitTests()) {
            $order = Order::query()->find($orderId);
            if ($order) {
                $this->deliver($order);
            }

            return;
        }

        $php = escapeshellarg(PHP_BINARY);
        $artisan = escapeshellarg(base_path('artisan'));
        $log = escapeshellarg(storage_path('logs/order-mail.log'));
        $command = "$php $artisan orders:notify $orderId >> $log 2>&1";

        if (PHP_OS_FAMILY === 'Windows') {
            pclose(popen('start /B "" '.$command, 'r'));

            return;
        }

        exec($command.' &');
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
    }
}
