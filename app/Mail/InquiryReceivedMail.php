<?php

namespace App\Mail;

use App\Models\ProductInquiry;
use App\Models\SiteTheme;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProductInquiry $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('store.inquiry.admin_subject', ['product' => $this->inquiry->product?->name]));
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.inquiry-received',
            with: ['theme' => SiteTheme::current()],
        );
    }
}
