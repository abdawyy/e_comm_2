<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\SiteTheme;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignupCouponMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Coupon $coupon, public string $mailLocale) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('store.signup.mail_subject', ['code' => $this->coupon->code], $this->mailLocale));
    }

    public function content(): Content
    {
        return new Content(view: 'mail.signup-coupon', with: [
            'theme' => SiteTheme::current(),
            'locale' => $this->mailLocale,
        ]);
    }
}
