<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutLayoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function checkout_uses_a_stacking_layout_for_the_order_summary(): void
    {
        $product = Product::factory()->create([
            'name' => ['en' => 'Sidamo coffee', 'ar' => 'قهوة سيدامو'],
            'price_per_unit' => '140.0000',
            'unit' => 'pack',
        ]);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '1',
            'unit' => 'pack',
        ])->assertRedirect();

        $this->get('/checkout')
            ->assertOk()
            ->assertSee('checkout-layout', false)
            ->assertSee('checkout-summary', false)
            ->assertSee('Sidamo coffee');
    }
}
