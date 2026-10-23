<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Models\User;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomerInsightsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_customer_page_shows_locations_devices_hours_and_products(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        $customer = User::factory()->create(['name' => 'Lina Farouk']);
        $product = Product::factory()->create(['name' => ['en' => 'Atlas Olives', 'ar' => 'زيتون أطلس']]);

        $visitor = SiteVisitor::query()->create([
            'session_id' => 'sess-lina',
            'user_id' => $customer->id,
            'device_type' => 'mobile',
            'country' => 'Egypt',
            'region' => 'Cairo Governorate',
            'city' => 'Zamalek',
            'first_seen_at' => now()->subDays(3),
            'last_seen_at' => now(),
        ]);

        $browse = Carbon::now('Africa/Cairo')->setTime(21, 10);
        SitePageView::query()->create([
            'site_visitor_id' => $visitor->id,
            'path' => '/products/atlas-olives',
            'viewed_at' => $browse->copy()->utc(),
        ]);
        ProductView::query()->create([
            'session_id' => 'sess-lina',
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'visited_at' => $browse->copy()->utc(),
        ]);

        $purchase = Carbon::now('Africa/Cairo')->setTime(18, 5);
        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'confirmed',
            'total' => '42.0000',
        ]);
        $order->forceFill(['created_at' => $purchase->copy()->utc()])->save();
        OrderItem::query()->create([
            'order_id' => $order->id,
            'kind' => 'product',
            'product_id' => $product->id,
            'name' => ['en' => 'Atlas Olives', 'ar' => 'زيتون أطلس'],
            'unit' => 'piece',
            'unit_price' => '42.0000',
            'quantity' => '1.0000',
            'line_base' => '42.0000',
            'services_fee' => '0.0000',
            'packaging_fee' => '0.0000',
            'line_subtotal' => '42.0000',
        ]);

        $this->actingAs($admin)
            ->get('/admin/users/'.$customer->id)
            ->assertOk()
            ->assertSee('Zamalek')
            ->assertSee('mobile')
            ->assertSee('Atlas Olives')
            ->assertSee('21')
            ->assertSee('18');
    }

    #[Test]
    public function customer_analysis_lists_each_customers_orders_and_places(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
        $customer = User::factory()->create(['name' => 'Nour Adel']);
        Order::factory()->create([
            'user_id' => $customer->id,
            'reference' => 'ZX-ZAYED',
            'address' => 'Villa 4, Sheikh Zayed',
            'city_name' => 'Giza',
            'status' => 'confirmed',
            'total' => '90.0000',
        ]);
        SiteVisitor::query()->create([
            'session_id' => 'sess-sheraton',
            'area' => 'Sheraton',
            'city' => 'Cairo',
            'country' => 'Egypt',
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/customer-analysis')
            ->assertOk()
            ->assertSee('Nour Adel')
            ->assertSee('ZX-ZAYED')
            ->assertSee('Sheikh Zayed')
            ->assertSee('Sheraton');
    }
}
