<?php

namespace App\Actions;

use App\Contracts\SmsSender;
use App\Mail\AdminOrderMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
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

        $this->notifyAdmin($order);

        $this->sms->send(
            $order->customer_phone,
            __('store.sms.order_confirmed', ['reference' => $order->reference], $order->locale),
        );

        $order->forceFill(['confirmation_sent_at' => now()])->save();
    }

    public function notifyAdmin(Order $order): void
    {
        $key = 'orders.admin_mail.'.$order->id;

        if (Cache::has($key)) {
            return;
        }

        $admins = User::query()->where('is_admin', true)->pluck('email')
            ->push(config('store.admin_email'))
            ->filter()
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->unique()
            ->values();

        if ($admins->isEmpty()) {
            return;
        }

        Mail::to($admins->all())->send(new AdminOrderMail($order));
        Cache::forever($key, true);
    }
}
