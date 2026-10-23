<?php

namespace Tests\Feature;

use App\Models\DesignAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DesignAssetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_logo_design_asset_is_rendered_in_the_navbar(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('design/logo.png', 'logo');
        DesignAsset::factory()->create([
            'key' => 'logo',
            'image_path' => 'design/logo.png',
            'is_active' => true,
        ]);
        Cache::forget('design.assets.v2');

        $this->get('/')->assertOk()->assertSee('design/logo.png', false);
    }
}
