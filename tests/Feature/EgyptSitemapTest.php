<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EgyptSitemapTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_sitemap_lists_arabic_egypt_pages_and_robots_points_to_it(): void
    {
        $product = Product::factory()->create([
            'name' => ['en' => 'Cairo dates', 'ar' => 'بلح القاهرة'],
            'is_active' => true,
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('content-type', 'application/xml; charset=UTF-8')
            ->assertSee('hreflang="ar-EG"', false)
            ->assertSee(route('products.show', $product).'?locale=ar', false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false);

        $this->get('/')
            ->assertOk()
            ->assertSee('areaServed', false)
            ->assertSee('Egypt', false);
    }
}
