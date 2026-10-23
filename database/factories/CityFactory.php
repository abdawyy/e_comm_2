<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'name' => ['en' => $name, 'ar' => $name],
            'shipping_fee' => '25.0000',
            'is_active' => true,
            'sort_order' => 1,
        ];
    }
}
