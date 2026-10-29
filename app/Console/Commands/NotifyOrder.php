<?php

namespace App\Console\Commands;

use App\Listeners\HandleOrderCreated;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotifyOrder extends Command
{
    protected $signature = 'orders:notify {order}';

    protected $description = 'Send the invoice and order emails after checkout has already returned';

    public function handle(HandleOrderCreated $handler): int
    {
        $order = Order::query()->find($this->argument('order'));

        if (! $order) {
            return self::SUCCESS;
        }

        try {
            $handler->deliver($order);
        } catch (Throwable $exception) {
            Log::error('order.notifications_failed', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
