<?php

namespace Tests\Feature;

use App\Mail\InquiryReceivedMail;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductInquiryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function coffee_tools_are_requested_by_name_and_phone_instead_of_the_cart(): void
    {
        Mail::fake();
        $category = Category::factory()->create([
            'name' => ['en' => 'Coffee tools', 'ar' => 'أدوات القهوة'],
            'is_inquiry' => true,
        ]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Hand grinder', 'ar' => 'مطحنة يدوية'],
        ]);

        $this->postJson('/cart/items', [
            'product_id' => $product->id,
            'quantity' => '1',
        ])->assertStatus(422);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee(__('store.inquiry.request'), false)
            ->assertSee(__('store.inquiry.help'), false);

        $this->post(route('products.inquiries.store', $product), [
            'customer_name' => 'Nour Hassan',
            'customer_phone' => '01012345678',
            'message' => 'I need this for the shop.',
        ])->assertRedirect();

        $this->assertDatabaseHas('product_inquiries', [
            'product_id' => $product->id,
            'customer_name' => 'Nour Hassan',
            'customer_phone' => '01012345678',
            'status' => 'new',
        ]);

        Mail::assertSent(InquiryReceivedMail::class);
    }
}
