<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymobDisabledTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function checkout_hides_visa_when_paymob_is_disabled(): void
    {
        config(['payments.paymob.enabled' => false]);
        $product = Product::factory()->create();
        City::factory()->create();
        $this->postJson('/cart/items', ['product_id' => $product->id, 'quantity' => '1'])->assertOk();

        $this->get('/checkout')->assertOk()->assertDontSee('Visa / Mastercard');
    }
}
