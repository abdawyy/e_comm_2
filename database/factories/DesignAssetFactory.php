<?php

namespace Database\Factories;

use App\Models\DesignAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DesignAsset>
 */
class DesignAssetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => 'logo',
            'image_path' => 'design/logo.png',
            'alt' => ['en' => 'Logo', 'ar' => 'شعار'],
            'is_active' => true,
        ];
    }
}
