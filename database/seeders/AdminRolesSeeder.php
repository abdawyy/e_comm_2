<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminRolesSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $subjects = [
            'Category', 'Product', 'Bundle', 'Coupon', 'Order', 'City', 'PreparationService',
            'PackagingType', 'User', 'ContentString', 'SeoSetting', 'HomeBanner', 'SiteVisitor',
            'DesignAsset', 'SiteTheme', 'PaymentTransaction',
        ];
        $abilities = [
            'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore',
            'forceDelete', 'forceDeleteAny', 'restoreAny', 'replicate', 'reorder',
        ];

        $permissions = [];

        foreach ($subjects as $subject) {
            foreach ($abilities as $ability) {
                $permissions[] = Permission::findOrCreate(str($ability)->studly()->toString().':'.$subject, 'web');
            }
        }

        foreach (['View:TrafficDashboard', 'View:AdminGuide', 'View:SalesDashboard'] as $page) {
            $permissions[] = Permission::findOrCreate($page, 'web');
        }

        $all = Permission::query()->where('guard_name', 'web')->get();

        $pick = function (array $subjects, array $abilities): array {
            $names = [];

            foreach ($subjects as $subject) {
                foreach ($abilities as $ability) {
                    $names[] = str($ability)->studly()->toString().':'.$subject;
                }
            }

            return $names;
        };

        $sync = function (string $role, array $names): void {
            Role::findOrCreate($role, 'web')->syncPermissions($names);
        };

        $sync('super_admin', $all->pluck('name')->all());
        $sync('orders_manager', array_merge($pick(['Order'], $abilities), $pick(['User'], ['viewAny', 'view']), ['View:AdminGuide', 'View:SalesDashboard']));
        $sync('catalog_manager', array_merge($pick(['Product', 'Category', 'Bundle', 'PreparationService', 'PackagingType', 'City', 'Coupon'], $abilities), ['View:AdminGuide']));
        $sync('content_manager', array_merge($pick(['HomeBanner', 'ContentString', 'SeoSetting'], $abilities), ['View:AdminGuide']));
        $sync('design_manager', array_merge($pick(['DesignAsset', 'SiteTheme', 'HomeBanner'], $abilities), ['View:AdminGuide']));
        $sync('analytics_viewer', array_merge($pick(['SiteVisitor', 'User'], ['viewAny', 'view']), ['View:TrafficDashboard', 'View:AdminGuide', 'View:SalesDashboard']));
        $sync('finance_viewer', array_merge($pick(['Order', 'PaymentTransaction'], ['viewAny', 'view']), ['View:AdminGuide']));

        $admin = User::query()->firstOrNew(['email' => 'admin@example.test']);
        $admin->forceFill([
            'name' => 'Atlas Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'phone' => '01000000000',
            'is_admin' => true,
            'email_verified_at' => now(),
        ])->save();
        $admin->syncRoles(['super_admin']);

        $this->command?->info('Seeded admin admin@example.test / password');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
