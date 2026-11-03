<?php

namespace Tests\Feature;

use App\Filament\Admin\Widgets\CitiesChartWidget;
use App\Filament\Admin\Widgets\LatestOrdersWidget;
use App\Filament\Admin\Widgets\PaymentsChartWidget;
use App\Filament\Admin\Widgets\ProductViewsChartWidget;
use App\Filament\Admin\Widgets\TopProductsChartWidget;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\SalesReport;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardChartsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_dashboard_formats_order_totals_and_shows_the_extra_charts(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = \App\Models\User::query()->where('email', 'admin@example.test')->firstOrFail();
        $product = Product::factory()->create(['name' => ['en' => 'Sidamo coffee', 'ar' => 'قهوة سيدامو']]);
        $order = Order::factory()->create([
            'reference' => 'TG-3705972550',
            'customer_name' => 'Abdelrahman Mohamed',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'city_name' => 'Cairo',
            'total' => '350.0000',
            'subtotal' => '350.0000',
        ]);
        \App\Models\OrderItem::query()->create([
            'order_id' => $order->id,
            'kind' => 'product',
            'product_id' => $product->id,
            'name' => ['en' => 'Sidamo coffee', 'ar' => 'قهوة سيدامو'],
            'unit' => 'pack',
            'unit_price' => '350.0000',
            'quantity' => '1.0000',
            'line_base' => '350.0000',
            'services_fee' => '0.0000',
            'packaging_fee' => '0.0000',
            'line_subtotal' => '350.0000',
        ]);

        $summary = app(SalesReport::class)->summary();
        $this->assertSame(1, $summary['payments']['cod']);
        $this->assertSame('Sidamo coffee', $summary['products'][0]['name']);
        $this->assertSame('Cairo', $summary['cities'][0]['city']);

        $this->actingAs($admin);

        Livewire::test(LatestOrdersWidget::class)
            ->assertSee('TG-3705972550')
            ->assertSee('Abdelrahman Mohamed')
            ->assertSee('Pending')
            ->assertSee('350.00 EGP');

        Livewire::test(PaymentsChartWidget::class)->assertSee('Orders by payment');
        Livewire::test(TopProductsChartWidget::class)->assertSee('Top products');
        Livewire::test(CitiesChartWidget::class)->assertSee('Orders by city');
        Livewire::test(ProductViewsChartWidget::class)->assertSee('Product views');

        $this->get('/admin')->assertOk();
    }
}
