<?php

namespace Database\Factories;

use App\Models\SiteTheme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteTheme>
 */
class SiteThemeFactory extends Factory
{
    public function definition(): array
    {
        return config('store.theme_defaults');
    }
}
