<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\City;
use App\Models\Product;
use App\Models\StoreSetting;
use Database\Seeders\EgyptPlacesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstapayAndPlaceFeeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function egypt_places_keep_each_city_inside_its_governorate(): void
    {
        $this->seed(EgyptPlacesSeeder::class);

        $sheraton = Area::query()->where('key', 'cairo-sheraton')->firstOrFail();
        $rehab = Area::query()->where('key', 'cairo-rehab')->firstOrFail();
        $obour = Area::query()->where('key', 'qalyubia-obour')->firstOrFail();

        $this->assertSame('Cairo', $sheraton->city->getTranslation('name', 'en'));
        $this->assertSame('75.0000', (string) $sheraton->shipping_fee);
        $this->assertSame('100.0000', (string) $rehab->shipping_fee);
        $this->assertSame('Qalyubia', $obour->city->getTranslation('name', 'en'));
        $this->assertSame(27, City::query()->count());
    }

    #[Test]
    public function checkout_charges_the_place_fee_and_lists_instapay_when_it_is_on(): void
    {
        $product = Product::factory()->create(['price_per_unit' => '10.0000', 'stock_quantity' => '5.0000']);
        $cairo = City::factory()->create(['name' => ['en' => 'Cairo', 'ar' => 'القاهرة'], 'shipping_fee' => '35.0000']);
        $sheraton = Area::query()->create([
            'city_id' => $cairo->id,
            'key' => 'cairo-sheraton',
            'name' => ['en' => 'Sheraton', 'ar' => 'شيراتون'],
            'shipping_fee' => '75.0000',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        StoreSetting::query()->create([
            'instapay_number' => '01099887766',
            'instapay_active' => true,
        ]);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '1',
        ])->assertRedirect();

        $this->get('/cart')
            ->assertOk()
            ->assertSee('instapay-logo', false)
            ->assertSee('01099887766');

        $this->get('/checkout')
            ->assertOk()
            ->assertSee('instapay-logo', false)
            ->assertSee('01099887766')
            ->assertSee('data-fee="75.0000"', false);

        $this->get('/checkout');
        $this->post('/checkout', [
            'customer_name' => 'Nour Hassan',
            'customer_phone' => '01012345678',
            'city_id' => $cairo->id,
            'area_id' => $sheraton->id,
            'address' => '12 Sheraton',
            'payment_gateway' => 'instapay',
            'nonce' => session('checkout.nonce'),
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'payment_gateway' => 'instapay',
            'payment_status' => 'unpaid',
            'city_id' => $cairo->id,
            'area_id' => $sheraton->id,
            'shipping_fee' => '75.0000',
            'total' => '85.0000',
        ]);
    }

    #[Test]
    public function instapay_stays_off_the_cart_when_it_is_inactive(): void
    {
        $product = Product::factory()->create(['price_per_unit' => '10.0000', 'stock_quantity' => '5.0000']);
        StoreSetting::query()->create([
            'instapay_number' => '01099887766',
            'instapay_active' => false,
        ]);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '1',
        ])->assertRedirect();

        $this->get('/cart')
            ->assertOk()
            ->assertDontSee('instapay-logo', false)
            ->assertDontSee('01099887766');
    }
}
