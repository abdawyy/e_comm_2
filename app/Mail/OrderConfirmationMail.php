<?php

namespace App\Mail;

use App\Models\DesignAsset;
use App\Models\Order;
use App\Models\SiteTheme;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $invoiceUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('store.mail.confirmed_subject', ['reference' => $this->order->reference], $this->order->locale));
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order-confirmation',
            with: [
                'theme' => SiteTheme::current(),
                'logo' => DesignAsset::urlFor('logo'),
            ],
        );
    }
}
