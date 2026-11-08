<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\SiteTheme;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaymentChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $fromStatus, public string $toStatus) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('store.mail.payment_subject', ['reference' => $this->order->reference], $this->order->locale ?: 'en'));
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-payment',
            with: [
                'theme' => SiteTheme::current(),
                'locale' => $this->order->locale ?: 'en',
            ],
        );
    }
}
