<?php

namespace App\Mail;

use App\Actions\GenerateInvoiceAction;
use App\Models\DesignAsset;
use App\Models\Order;
use App\Models\SiteTheme;
use App\Services\DecimalMath;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        $brand = (string) (SiteTheme::current()->brand_name ?: config('mail.from.name'));
        $from = (string) config('mail.from.address');

        return new Envelope(
            from: new Address($from, $brand),
            replyTo: [new Address($from, $brand)],
            subject: __('store.mail.confirmed_subject', ['reference' => $this->order->reference], $this->order->locale ?: 'ar'),
            using: [
                function (Email $message): void {
                    $headers = $message->getHeaders();

                    if (! $headers->has('Auto-Submitted')) {
                        $headers->addTextHeader('Auto-Submitted', 'auto-generated');
                    }
                },
            ],
        );
    }

    public function content(): Content
    {
        $this->order->loadMissing('items');
        $logo = DesignAsset::urlFor('logo');

        return new Content(
            view: 'mail.order-confirmation',
            text: 'mail.order-confirmation-text',
            with: [
                'theme' => SiteTheme::current(),
                'logo' => is_string($logo) && str_starts_with($logo, 'https://') ? $logo : null,
                'math' => app(DecimalMath::class),
                'locale' => $this->order->locale ?: 'ar',
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
