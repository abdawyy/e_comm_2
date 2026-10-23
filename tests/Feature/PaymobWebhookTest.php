<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymobWebhookTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_accepts_a_valid_hmac_rejects_a_bad_one_flags_amount_mismatch_and_is_idempotent(): void
    {
        config(['payments.paymob.hmac_secret' => 'hmac-secret']);
        Mail::fake();

        $order = Order::factory()->create([
            'payment_gateway' => 'paymob_card',
            'customer_email' => 'nour@example.test',
            'total' => '10.0000',
            'amount_cents' => 1000,
        ]);
        $order->forceFill(['payment_status' => 'pending'])->save();
        PaymentTransaction::query()->create([
            'order_id' => $order->id,
            'gateway' => 'paymob_card',
            'special_reference' => $order->reference,
            'amount_cents' => 1000,
            'status' => 'pending',
        ]);

        $payload = $this->payload($order->reference, 1000, true, 9001);
        $this->postJson('/payments/paymob/webhook?hmac=not-valid', ['obj' => $payload])->assertStatus(401);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_status' => 'pending']);

        $this->postJson('/payments/paymob/webhook?hmac='.$this->sign($payload), ['obj' => $payload])->assertOk();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_status' => 'paid']);
        Mail::assertSent(OrderConfirmationMail::class, 1);

        $this->postJson('/payments/paymob/webhook?hmac='.$this->sign($payload), ['obj' => $payload])->assertOk();
        Mail::assertSent(OrderConfirmationMail::class, 1);

        $other = Order::factory()->create(['payment_gateway' => 'paymob_card', 'reference' => 'ZZ-0000000001']);
        $other->forceFill(['payment_status' => 'pending'])->save();
        PaymentTransaction::query()->create([
            'order_id' => $other->id,
            'gateway' => 'paymob_card',
            'special_reference' => $other->reference,
            'amount_cents' => 1000,
            'status' => 'pending',
        ]);
        $mismatch = $this->payload($other->reference, 50, true, 9002);
        $this->postJson('/payments/paymob/webhook?hmac='.$this->sign($mismatch), ['obj' => $mismatch])->assertOk();
        $this->assertDatabaseHas('payment_transactions', [
            'order_id' => $other->id,
            'status' => 'amount_mismatch',
        ]);
        $this->assertDatabaseHas('orders', ['id' => $other->id, 'payment_status' => 'pending']);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(string $reference, int $cents, bool $success, int $id): array
    {
        return [
            'amount_cents' => $cents,
            'created_at' => '2026-10-22T12:00:00.000000Z',
            'currency' => 'EGP',
            'error_occured' => false,
            'has_parent_transaction' => false,
            'id' => $id,
            'integration_id' => 445566,
            'is_3d_secure' => true,
            'is_auth' => false,
            'is_capture' => false,
            'is_refunded' => false,
            'is_standalone_payment' => true,
            'is_voided' => false,
            'order' => ['id' => 77, 'merchant_order_id' => $reference],
            'owner' => 1,
            'pending' => false,
            'source_data' => ['pan' => '2346', 'sub_type' => 'Visa', 'type' => 'card'],
            'success' => $success,
        ];
    }

    /**
     * @param  array<string, mixed>  $transaction
     */
    private function sign(array $transaction): string
    {
        $bool = fn (mixed $value): string => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        $concat = $transaction['amount_cents']
            .$transaction['created_at']
            .$transaction['currency']
            .$bool($transaction['error_occured'])
            .$bool($transaction['has_parent_transaction'])
            .$transaction['id']
            .$transaction['integration_id']
            .$bool($transaction['is_3d_secure'])
            .$bool($transaction['is_auth'])
            .$bool($transaction['is_capture'])
            .$bool($transaction['is_refunded'])
            .$bool($transaction['is_standalone_payment'])
            .$bool($transaction['is_voided'])
            .$transaction['order']['id']
            .$transaction['owner']
            .$bool($transaction['pending'])
            .$transaction['source_data']['pan']
            .$transaction['source_data']['sub_type']
            .$transaction['source_data']['type']
            .$bool($transaction['success']);

        return hash_hmac('sha512', $concat, 'hmac-secret');
    }
}
