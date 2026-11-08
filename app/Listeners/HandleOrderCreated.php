<?php

namespace App\Listeners;

use App\Actions\SendOrderNotifications;
use App\Events\OrderCreated;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Throwable;

class HandleOrderCreated
{
    public function __construct(
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

        dispatch(function () use ($orderId): void {
            $order = Order::query()->find($orderId);

            if (! $order) {
                return;
            }

            try {
                app(self::class)->deliver($order);
            } catch (Throwable $exception) {
                $order->forceFill([
                    'mail_status' => 'failed',
                    'mail_error' => $exception->getMessage(),
                ])->save();
                Log::error('order.notifications_failed', [
                    'order_id' => $order->id,
                    'message' => $exception->getMessage(),
                ]);
            }
        })->afterResponse();
    }

    public function deliver(Order $order): void
    {
        if ($order->payment_gateway === 'cod') {
            $this->notifications->send($order->fresh() ?? $order);

            return;
        }

        $order = $order->fresh() ?? $order;
        $this->notifications->notifyAdmin($order);
        $order->forceFill([
            'mail_status' => filled($order->customer_email) ? 'sent' : 'failed',
            'mail_error' => filled($order->customer_email) ? null : 'Customer has no email',
        ])->save();
    }
}
