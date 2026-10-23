<?php

namespace Tests\Feature;

use App\Filament\Admin\Pages\EditSiteTheme;
use App\Models\Order;
use App\Models\SiteTheme;
use App\Models\User;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RoleEnforcementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function analytics_and_design_roles_are_enforced(): void
    {
        $this->seed(AdminRolesSeeder::class);
        SiteTheme::factory()->create();

        $analytics = User::factory()->create();
        $analytics->forceFill(['is_admin' => true])->save();
        $analytics->assignRole('analytics_viewer');

        $this->actingAs($analytics);
        $this->get('/admin/traffic')->assertOk();
        $this->get('/admin/products/create')->assertForbidden();

        $designer = User::factory()->create();
        $designer->forceFill(['is_admin' => true])->save();
        $designer->assignRole('design_manager');
        $order = Order::factory()->create();

        $this->actingAs($designer);
        Livewire::test(EditSiteTheme::class)
            ->set('data.brand_name', 'Atlas Market')
            ->set('data.primary', '#14532d')
            ->set('data.primary_dark', '#052e16')
            ->set('data.primary_light', '#4ade80')
            ->set('data.accent', '#f97316')
            ->set('data.accent_light', '#fdba74')
            ->set('data.canvas', '#f8fafc')
            ->set('data.surface', '#f1f5f9')
            ->set('data.border', '#e2e8f0')
            ->set('data.success', '#10b981')
            ->set('data.danger', '#ef4444')
            ->set('data.theme_color', '#14532d')
            ->set('data.use_anyway', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('site_themes', ['primary' => '#14532d']);
        $this->get('/admin/orders/'.$order->id.'/edit')->assertForbidden();
    }
}
