<?php

namespace App\Actions;

use App\Contracts\SmsSender;
use App\Mail\AdminOrderMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendOrderNotifications
{
    public function __construct(private SmsSender $sms) {}

    public function send(Order $order): void
    {
        $order->refresh();

        if ($order->confirmation_sent_at) {
            return;
        }

        $url = URL::temporarySignedRoute(
            'invoices.download',
            now()->addDays((int) config('store.invoice_ttl_days', 14)),
            ['order' => $order->id],
        );

        if (filled($order->customer_email)) {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order, $url));
        }

        $admins = User::query()->where('is_admin', true)->pluck('email')->filter()->all();

        if ($admins !== []) {
            Mail::to($admins)->send(new AdminOrderMail($order));
        }

        $this->sms->send(
            $order->customer_phone,
            __('store.sms.order_confirmed', ['reference' => $order->reference], $order->locale),
        );

        $order->forceFill(['confirmation_sent_at' => now()])->save();
    }
}
