<?php

namespace Tests\Feature;

use App\Models\SiteVisitor;
use App\Services\VisitorLocator;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrafficLocationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_visit_is_stored_as_country_city_and_place(): void
    {
        Http::fake([
            'ip-api.com/*' => Http::response([
                'status' => 'success',
                'country' => 'Egypt',
                'countryCode' => 'EG',
                'regionName' => 'Cairo Governorate',
                'city' => 'Cairo',
                'lat' => 30.09,
                'lon' => 31.37,
            ]),
            'nominatim.openstreetmap.org/*' => Http::response([
                'display_name' => 'Sheraton, Heliopolis, Cairo, Egypt',
                'address' => [
                    'neighbourhood' => 'Sheraton',
                    'suburb' => 'Heliopolis',
                    'city' => 'Cairo',
                    'country' => 'Egypt',
                ],
            ]),
            'api.bigdatacloud.net/*' => Http::response([
                'city' => 'Cairo',
                'locality' => 'Sheraton',
            ]),
        ]);

        $place = app(VisitorLocator::class)->locate('197.44.10.10');

        $this->assertSame([
            'country_code' => 'EG',
            'country' => 'Egypt',
            'region' => 'Cairo Governorate',
            'city' => 'Cairo',
            'area' => 'Sheraton',
        ], $place);
    }

    #[Test]
    public function traffic_and_the_visitor_list_show_the_newest_visit_first(): void
    {
        $this->seed(AdminRolesSeeder::class);
        $admin = \App\Models\User::query()->where('email', 'admin@example.test')->firstOrFail();

        SiteVisitor::query()->create([
            'session_id' => 'old-desktop',
            'device_type' => 'desktop',
            'country' => 'Egypt',
            'city' => 'Giza',
            'area' => 'Dokki',
            'first_seen_at' => now()->subDays(4),
            'last_seen_at' => now()->subDays(4),
        ]);
        SiteVisitor::query()->create([
            'session_id' => 'new-mobile',
            'device_type' => 'mobile',
            'country' => 'EG',
            'city' => 'Cairo',
            'area' => 'Sheraton',
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/traffic')
            ->assertOk()
            ->assertSeeInOrder(['Sheraton', 'Dokki'])
            ->assertSee('Egypt, Cairo, Sheraton');

        $this->actingAs($admin)
            ->get('/admin/site-visitors')
            ->assertOk()
            ->assertSeeInOrder(['Sheraton', 'Dokki'])
            ->assertSee('Egypt, Cairo, Sheraton');
    }
}
