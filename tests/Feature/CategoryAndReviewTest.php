<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryAndReviewTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_home_page_links_each_department_icon_and_reviews_wait_for_approval(): void
    {
        $category = Category::factory()->create([
            'name' => ['en' => 'Espresso', 'ar' => 'الإسبريسو'],
            'icon_path' => 'categories/icons/espresso.jpg',
        ]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('store-department', false)
            ->assertSee('الإسبريسو', false);

        $this->post(route('products.reviews.store', $product), [
            'author_name' => 'Nour',
            'rating' => 5,
            'body' => 'طعم نظيف',
        ])->assertRedirect();

        $this->assertFalse(ProductReview::query()->first()->is_approved);
        $this->get(route('products.show', $product))->assertDontSee('طعم نظيف');

        ProductReview::query()->first()->update(['is_approved' => true]);
        $this->get(route('products.show', $product))->assertSee('طعم نظيف');
    }
}
