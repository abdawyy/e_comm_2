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

        $plain = Order::factory()->create(['reference' => 'DL-NONE']);

        $this->actingAs($admin)
            ->get(route('delivery.label', $order))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($admin)
            ->get(route('delivery.label', $plain))
            ->assertNotFound();

        $this->actingAs($admin)
            ->get('/admin/delivery')
            ->assertOk()
            ->assertSee((string) $carrier->name, false)
            ->assertSee(route('delivery.label', $order), false)
            ->assertDontSee(route('delivery.label', $plain), false);

        $this->actingAs($admin)
            ->get('/admin/orders/'.$order->id)
            ->assertOk()
            ->assertDontSee(route('delivery.label', $order), false);
    }

    #[Test]
    public function the_delivery_page_includes_the_carrier_assignment_form(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = \App\Models\User::query()->where('email', 'admin@example.test')->firstOrFail();
        $order = Order::factory()->create([
            'reference' => 'DL-WAIT',
            'customer_name' => 'Eyad Omar',
        ]);

        $this->actingAs($admin)
            ->get('/admin/delivery')
            ->assertOk()
            ->assertSee(__('store.delivery.assign'), false)
            ->assertSee($order->reference, false)
            ->assertSee(__('store.delivery.waiting'), false)
            ->assertSee(__('store.delivery.search'), false);
    }

    #[Test]
    public function the_storefront_defaults_to_arabic(): void
    {
        $this->get('/')->assertOk()->assertSee('lang="ar"', false);
    }
}
