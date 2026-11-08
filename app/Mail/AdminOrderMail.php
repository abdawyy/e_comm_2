<?php

namespace App\Mail;

use App\Actions\GenerateInvoiceAction;
use App\Models\Order;
use App\Models\SiteTheme;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('store.mail.admin_subject', ['reference' => $this->order->reference], $this->order->locale));
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin-order',
            with: [
                'theme' => SiteTheme::current(),
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = app(GenerateInvoiceAction::class)->execute($this->order);

        return [
            Attachment::fromData(fn () => $pdf, $this->order->reference.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
