<?php

namespace Tests\Feature;

use App\Models\SiteTheme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SiteThemeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function saving_a_theme_updates_the_storefront_css_variables(): void
    {
        $theme = SiteTheme::factory()->create(array_merge(config('store.theme_defaults'), [
            'primary' => '#123456',
            'theme_color' => '#123456',
        ]));

        $theme->update(['primary' => '#654321']);
        Cache::forget('site.theme.v2');

        $this->get('/')->assertOk()->assertSee('--color-brand: #654321', false);
    }
}
