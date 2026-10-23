<?php

namespace Tests\Feature;

use App\Listeners\HandleOrderCreated;
use App\Models\City;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutCodTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cash_checkout_creates_an_order_and_queues_the_listener(): void
    {
        Queue::fake();
        $product = Product::factory()->create(['price_per_unit' => '10.0000', 'stock_quantity' => '5.0000']);
        $city = City::factory()->create(['shipping_fee' => '25.0000']);

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '2',
        ])->assertOk();

        $this->get('/checkout')->assertOk();

        $this->post('/checkout', [
            'customer_name' => 'Nour Hassan',
            'customer_email' => 'nour@example.test',
            'customer_phone' => '01012345678',
            'city_id' => $city->id,
            'address' => '12 Nile Street',
            'payment_gateway' => 'cod',
            'nonce' => session('checkout.nonce'),
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'payment_gateway' => 'cod',
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'total' => '45.0000',
        ]);

        Queue::assertPushed(\Illuminate\Events\CallQueuedListener::class, function ($job) {
            return $job->class === HandleOrderCreated::class;
        });
    }
}
