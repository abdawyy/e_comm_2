<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ShippingCarrier;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeliveryLabelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function assigning_a_carrier_stores_its_optional_fee_and_prints_a_label(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = \App\Models\User::query()->where('email', 'admin@example.test')->firstOrFail();
        $carrier = ShippingCarrier::query()->create([
            'code' => 'aramex-test',
            'name' => ['en' => 'Aramex', 'ar' => 'أرامكس'],
            'fee' => '15.0000',
            'is_active' => true,
        ]);
        $order = Order::factory()->create([
            'locale' => 'en',
            'customer_name' => 'Eyad Omar',
            'reference' => 'DL-0001',
        ]);

        $order->shipping_carrier_id = $carrier->id;
        $order->save();
        $order->refresh();

        $this->assertSame('15.0000', (string) $order->shipping_label_fee);

        $this->actingAs($admin)
            ->get(route('delivery.label', $order))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    #[Test]
    public function the_storefront_defaults_to_arabic(): void
    {
        $this->get('/')->assertOk()->assertSee('lang="ar"', false);
    }
}
