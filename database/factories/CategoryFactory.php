<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ['en' => $name, 'ar' => $name],
            'description' => ['en' => fake()->sentence(), 'ar' => fake()->sentence()],
            'is_active' => true,
            'sort_order' => 1,
        ];
    }
}
