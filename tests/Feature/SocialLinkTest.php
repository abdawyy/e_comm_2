<?php

namespace Tests\Feature;

use App\Models\SocialLink;
use App\Models\User;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SocialLinkTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_footer_shows_active_https_links_and_hides_unsafe_ones(): void
    {
        SocialLink::query()->where('platform', 'instagram')->update([
            'url' => 'https://instagram.com/atlas',
            'is_active' => true,
        ]);
        SocialLink::query()->where('platform', 'facebook')->update([
            'url' => 'javascript:alert(1)',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('https://instagram.com/atlas', false)
            ->assertSee('aria-label="Instagram"', false)
            ->assertDontSee('javascript:alert', false);
    }

    #[Test]
    public function a_design_manager_can_open_the_social_links_page(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $designer = User::factory()->create();
        $designer->forceFill(['is_admin' => true])->save();
        $designer->assignRole('design_manager');

        $this->actingAs($designer)->get('/admin/social-links')->assertOk();
    }
}
