<?php

namespace Tests\Feature;

use App\Models\Order;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SalesAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function sales_analytics_lists_revenue_and_the_promo_code(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = \App\Models\User::query()->where('email', 'admin@example.test')->firstOrFail();
        Order::factory()->create([
            'coupon_code' => 'WELCOME10',
            'total' => '350.0000',
            'subtotal' => '350.0000',
            'status' => 'pending',
            'city_name' => 'Cairo',
        ]);

        $this->actingAs($admin)
            ->get('/admin/sales')
            ->assertOk()
            ->assertSee('350.00 EGP')
            ->assertSee('WELCOME10')
            ->assertSee('Promo codes');
    }
}
