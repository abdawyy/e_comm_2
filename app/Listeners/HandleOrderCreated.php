<?php

namespace App\Listeners;

use App\Actions\GenerateInvoiceAction;
use App\Actions\SendOrderNotifications;
use App\Events\OrderCreated;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Throwable;

class HandleOrderCreated
{
    public function __construct(
        private GenerateInvoiceAction $invoices,
        private SendOrderNotifications $notifications,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $orderId = $event->order->getKey();

        app()->terminating(function () use ($orderId): void {
            $order = Order::query()->find($orderId);

            if (! $order) {
                return;
            }

            try {
                $this->deliver($order);
            } catch (Throwable $exception) {
                Log::error('order.notifications_failed', [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                ]);
            }
        });
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
