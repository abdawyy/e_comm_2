<?php

namespace Tests\Feature;

use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AnalyticsExportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_admin_can_export_all_analytics_as_excel_and_pdf(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = \App\Models\User::query()->where('email', 'admin@example.test')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.analytics.export', ['report' => 'all', 'format' => 'xlsx']))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->actingAs($admin)
            ->get(route('admin.analytics.export', ['report' => 'sales', 'format' => 'pdf']))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    #[Test]
    public function a_shopper_cannot_export_analytics(): void
    {
        $shopper = \App\Models\User::factory()->create(['is_admin' => false]);

        $this->actingAs($shopper)
            ->get(route('admin.analytics.export', ['report' => 'all', 'format' => 'pdf']))
            ->assertForbidden();
    }
}
