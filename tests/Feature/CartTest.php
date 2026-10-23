<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_adds_merges_applies_a_coupon_and_rejects_stock(): void
    {
        $product = Product::factory()->create([
            'price_per_unit' => '10.0000',
            'stock_quantity' => '5.0000',
            'unit' => 'piece',
        ]);
        Coupon::factory()->create(['code' => 'WELCOME10', 'type' => 'percent', 'value' => '10.0000']);

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '1',
            'unit' => 'piece',
        ])->assertOk()->assertJsonPath('lines', 1);

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '1',
            'unit' => 'piece',
        ])->assertOk()->assertJsonPath('lines', 1)->assertJsonPath('quote.lines.0.quantity', '2.0000');

        $this->postJson('/cart/coupon', ['code' => 'WELCOME10'])
            ->assertOk()
            ->assertJsonPath('quote.discount', '2.0000')
            ->assertJsonPath('quote.total', '18.0000');

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '10',
        ])->assertStatus(422);
    }
}
