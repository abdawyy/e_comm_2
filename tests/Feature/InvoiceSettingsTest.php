<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\SiteTheme;
use App\Models\StoreSetting;
use App\Services\DecimalMath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvoiceSettingsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_invoice_uses_the_saved_text_and_colors(): void
    {
        $setting = StoreSetting::query()->first() ?? new StoreSetting;
        $setting->invoice_pdf = [
            'accent' => '#112233',
            'titles' => '#aa1166',
            'ar' => ['tagline' => 'سطر المحمصة', 'title' => 'فاتورة المتجر'],
            'show_tax' => true,
            'tax_percent' => '14',
            'seller_phone' => '01099999999',
        ];
        $setting->save();

        $order = Order::factory()->create([
            'locale' => 'ar',
            'subtotal' => '100.0000',
            'total' => '100.0000',
        ]);

        $html = view('pdf.invoice', [
            'order' => $order->load('items'),
            'theme' => SiteTheme::current(),
            'logo' => null,
            'math' => app(DecimalMath::class),
            'locale' => 'ar',
        ])->render();

        $this->assertStringContainsString('سطر المحمصة', $html);
        $this->assertStringContainsString('فاتورة المتجر', $html);
        $this->assertStringContainsString('#112233', $html);
        $this->assertStringContainsString('#aa1166', $html);
        $this->assertStringContainsString('01099999999', $html);
        $this->assertStringContainsString('14.00 EGP', $html);
    }
}
