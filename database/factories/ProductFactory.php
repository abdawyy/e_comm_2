<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'sku' => 'SKU-'.fake()->unique()->numerify('#####'),
            'name' => ['en' => $name, 'ar' => $name],
            'description' => ['en' => fake()->sentence(), 'ar' => fake()->sentence()],
            'price_per_unit' => '10.0000',
            'unit' => 'piece',
            'track_stock' => true,
            'stock_quantity' => '20.0000',
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
