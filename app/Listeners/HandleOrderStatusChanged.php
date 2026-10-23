<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Mail\OrderStatusChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class HandleOrderStatusChanged implements ShouldQueue
{
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order->fresh();

        if (! $order || blank($order->customer_email)) {
            return;
        }

        Mail::to($order->customer_email)->send(new OrderStatusChangedMail($order, $event->from, $event->to));
    }
}
