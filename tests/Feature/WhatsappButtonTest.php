<?php

namespace Tests\Feature;

use App\Models\StoreSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WhatsappButtonTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_store_shows_a_whatsapp_button_for_the_admin_number(): void
    {
        $setting = StoreSetting::query()->first() ?? new StoreSetting;
        $setting->whatsapp_number = '01011111111';
        $setting->announcement_active = true;
        $setting->announcement_ar = 'شحن مجاني كود خصم CA1';
        $setting->announcement_en = 'Free shipping code CA1';
        $setting->announcement_color = '#112233';
        $setting->announcement_opacity = 40;
        $setting->save();

        $this->get('/')
            ->assertOk()
            ->assertSee('https://wa.me/201011111111', false)
            ->assertSee('store-whatsapp', false)
            ->assertSee('شحن مجاني كود خصم CA1', false)
            ->assertSee('store-announce', false)
            ->assertSee('rgba(17,34,51,0.40)', false)
            ->assertDontSee('viewBox="0 0 640 512"', false);
    }
}
