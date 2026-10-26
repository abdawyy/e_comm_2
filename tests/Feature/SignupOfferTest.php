<?php

namespace Tests\Feature;

use App\Mail\SignupCouponMail;
use App\Models\Coupon;
use App\Models\SignupOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SignupOfferTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_popup_uses_the_admin_delay_and_emails_the_selected_coupon_once(): void
    {
        $coupon = Coupon::factory()->create(['code' => 'HELLO10', 'type' => 'percent', 'value' => '10.0000', 'is_active' => true]);
        $offer = SignupOffer::query()->first() ?? new SignupOffer;
        $offer->fill([
            'is_active' => true,
            'delay_seconds' => 15,
            'coupon_id' => $coupon->id,
            'heading_en' => 'Sign up and save',
            'heading_ar' => 'سجل واحصل على خصم',
            'body_en' => 'Leave your email and we will send your discount coupon.',
            'body_ar' => 'اترك بريدك وسنرسل كوبون الخصم.',
        ])->save();

        Mail::fake();

        $this->get('/')->assertOk()->assertSee('data-delay="15"', false)->assertSee('Sign up and save');

        $this->postJson('/signup-offer', ['email' => 'nour@example.test'])->assertOk()->assertJsonPath('ok', true);
        Mail::assertSent(SignupCouponMail::class, fn (SignupCouponMail $mail) => $mail->hasTo('nour@example.test') && $mail->coupon->code === 'HELLO10');

        $this->postJson('/signup-offer', ['email' => 'nour@example.test'])->assertOk();
        Mail::assertSent(SignupCouponMail::class, 1);
    }
}
