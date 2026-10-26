<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContentString;
use App\Models\Product;
use App\Models\SeoSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SeedCoffeeProductsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_coffee_command_adds_products_and_images(): void
    {
        Storage::fake('public');
        Category::factory()->create(['name' => ['en' => 'Drinks', 'ar' => 'مشروبات'], 'is_active' => true]);

        $this->assertSame(0, Artisan::call('store:coffee'));

        $product = Product::query()->where('sku', 'ATL-YIRG')->first();
        $this->assertNotNull($product);
        $this->assertSame('products/ATL-YIRG.svg', $product->image_path);
        Storage::disk('public')->assertExists('products/ATL-YIRG.svg');
        Storage::disk('public')->assertExists('design/hero.svg');
        Storage::disk('public')->assertExists('design/og_default.svg');
        $this->assertSame('Coffee for the neighborhood', ContentString::query()->where('key', 'home.hero_title')->first()?->getTranslation('value', 'en'));
        $this->assertSame('Specialty coffee in Cairo | Atlas Market', SeoSetting::query()->where('route_key', 'home')->first()?->getTranslation('meta_title', 'en'));
        $this->assertSame(6, Product::query()->whereIn('sku', [
            'ATL-COFFEE', 'ATL-YIRG', 'ATL-SANTOS', 'ATL-ESPRESSO', 'ATL-TURK', 'ATL-DECAF',
        ])->count());
    }
}
