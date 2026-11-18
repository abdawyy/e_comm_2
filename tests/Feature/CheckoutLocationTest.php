<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Product;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutLocationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function checkout_stores_an_optional_building_floor_landmark_and_map_pin(): void
    {
        $product = Product::factory()->create(['price_per_unit' => '10.0000', 'stock_quantity' => '5.0000']);
        $city = City::factory()->create(['shipping_fee' => '0.0000']);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '1',
        ])->assertRedirect();

        $this->get('/checkout')
            ->assertOk()
            ->assertSee(__('store.checkout.building_number'), false)
            ->assertSee(__('store.checkout.floor'), false)
            ->assertSee(__('store.checkout.landmark'), false)
            ->assertSee('id="checkout-map"', false);

        $this->post('/checkout', [
            'customer_name' => 'Nour Hassan',
            'customer_phone' => '01012345678',
            'city_id' => $city->id,
            'address' => '12 Nile Street',
            'building_number' => '14',
            'floor' => '3',
            'landmark' => 'Near the mosque',
            'latitude' => '30.0444000',
            'longitude' => '31.2357000',
            'payment_gateway' => 'cod',
            'nonce' => session('checkout.nonce'),
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'building_number' => '14',
            'floor' => '3',
            'landmark' => 'Near the mosque',
            'latitude' => '30.0444000',
            'longitude' => '31.2357000',
        ]);
    }
}
