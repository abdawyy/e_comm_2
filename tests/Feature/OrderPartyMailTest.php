<?php

namespace Tests\Feature;

use App\Mail\OrderPaymentChangedMail;
use App\Mail\OrderStatusChangedMail;
use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderPartyMailTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function order_updates_go_to_the_customer_and_the_store_inbox(): void
    {
        Mail::fake();
        $store = StoreSetting::orderEmail();
        $order = Order::factory()->create([
            'customer_email' => 'nour@example.test',
            'status' => 'pending',
            'confirmation_sent_at' => now(),
        ]);

        $order->update(['status' => 'shipped']);

        Mail::assertSent(OrderStatusChangedMail::class, fn (OrderStatusChangedMail $mail) => $mail->hasTo('nour@example.test'));
        Mail::assertSent(OrderStatusChangedMail::class, fn (OrderStatusChangedMail $mail) => $mail->hasTo($store));

        $order->forceFill(['payment_status' => 'refunded'])->save();

        Mail::assertSent(OrderPaymentChangedMail::class, fn (OrderPaymentChangedMail $mail) => $mail->hasTo('nour@example.test'));
        Mail::assertSent(OrderPaymentChangedMail::class, fn (OrderPaymentChangedMail $mail) => $mail->hasTo($store));
    }
}
