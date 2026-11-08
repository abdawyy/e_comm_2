<?php

namespace App\Actions;

use App\Contracts\SmsSender;
use App\Mail\AdminOrderMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendOrderNotifications
{
    public function __construct(private SmsSender $sms) {}

    public function send(Order $order): void
    {
        $order->refresh();

        if ($order->confirmation_sent_at) {
            return;
        }

        if (blank($order->customer_email)) {
            $this->notifyAdmin($order);
            $order->forceFill([
                'mail_status' => 'failed',
                'mail_error' => 'Customer has no email',
            ])->save();

            return;
        }

        if (filled($order->customer_email)) {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
        }

        $this->notifyAdmin($order);

        $this->sms->send(
            $order->customer_phone,
            __('store.sms.order_confirmed', ['reference' => $order->reference], $order->locale),
        );

        $order->forceFill([
            'confirmation_sent_at' => now(),
            'mail_status' => 'sent',
            'mail_error' => null,
        ])->save();
    }

    public function sendInvoiceTo(Order $order, string $email): void
    {
        $order->refresh();

        Mail::to($email)->send(new OrderConfirmationMail($order));
        $order->forceFill([
            'confirmation_sent_at' => $order->confirmation_sent_at ?? now(),
            'mail_status' => 'sent',
            'mail_error' => null,
        ])->save();
    }

    /**
     * @return list<string>
     */
    public function parties(Order $order): array
    {
        return collect([$order->customer_email, StoreSetting::orderEmail()])
            ->filter(fn ($email) => filled($email))
            ->map(fn ($email) => strtolower(trim((string) $email)))
            ->unique()
            ->values()
            ->all();
    }

    public function sendCopy(Order $order, callable $message): void
    {
        foreach ($this->parties($order) as $email) {
            Mail::to($email)->send($message());
        }
    }

    public function notifyAdmin(Order $order): void
    {
        $email = StoreSetting::orderEmail();
        $key = 'orders.admin_mail.'.$order->id;

        if (blank($email) || Cache::has($key)) {
            return;
        }

        Mail::to($email)->send(new AdminOrderMail($order));
        Cache::forever($key, true);
    }
}
