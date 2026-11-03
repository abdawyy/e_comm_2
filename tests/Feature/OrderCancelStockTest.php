<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderCancelStockTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cancelling_an_order_puts_the_stock_back(): void
    {
        $product = Product::factory()->create([
            'track_stock' => true,
            'stock_quantity' => '4.0000',
            'unit' => 'piece',
        ]);
        $order = Order::factory()->create(['status' => 'confirmed']);
        $order->items()->create([
            'kind' => 'product',
            'product_id' => $product->id,
            'name' => ['en' => 'Oil', 'ar' => 'زيت'],
            'unit' => 'piece',
            'unit_price' => '10.0000',
            'quantity' => '1.0000',
            'line_base' => '10.0000',
            'services_fee' => '0.0000',
            'packaging_fee' => '0.0000',
            'line_subtotal' => '10.0000',
        ]);

        $order->update(['status' => 'cancelled']);

        $this->assertSame('5.0000', (string) $product->fresh()->stock_quantity);
    }
}
