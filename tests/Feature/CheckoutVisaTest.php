<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutVisaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function visa_checkout_creates_a_pending_order_and_redirects_to_paymob(): void
    {
        Queue::fake();
        config([
            'payments.paymob.enabled' => true,
            'payments.paymob.api_key' => 'api-key',
            'payments.paymob.secret_key' => 'secret-key',
            'payments.paymob.public_key' => 'pk_test',
            'payments.paymob.hmac_secret' => 'hmac-secret',
            'payments.paymob.card_integration_id' => '445566',
            'payments.paymob.base_url' => 'https://accept.paymob.com',
        ]);

        Http::fake([
            'accept.paymob.com/*' => Http::response([
                'id' => 'intention_1',
                'client_secret' => 'csk_test',
                'intention_order_id' => 99,
            ], 201),
        ]);

        $product = Product::factory()->create(['price_per_unit' => '10.0000', 'stock_quantity' => '4.0000']);
        $city = City::factory()->create(['shipping_fee' => '0.0000']);
        $this->postJson('/cart/items', ['product_id' => $product->id, 'quantity' => '1'])->assertOk();
        $this->get('/checkout');

        $response = $this->post('/checkout', [
            'customer_name' => 'Nour Hassan',
            'customer_email' => 'nour@example.test',
            'customer_phone' => '01012345678',
            'city_id' => $city->id,
            'address' => '12 Nile Street',
            'payment_gateway' => 'paymob_card',
            'nonce' => session('checkout.nonce'),
        ]);

        $response->assertRedirect('https://accept.paymob.com/unifiedcheckout/?publicKey=pk_test&clientSecret=csk_test');
        $this->assertDatabaseHas('orders', [
            'payment_gateway' => 'paymob_card',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('payment_transactions', [
            'status' => 'pending',
            'intention_id' => 'intention_1',
            'amount_cents' => 1000,
        ]);
    }
}
