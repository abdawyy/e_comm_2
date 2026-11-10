<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('name');
            $table->decimal('fee', 14, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('shipping_carrier_id')->nullable()->after('city_name')->constrained('shipping_carriers')->nullOnDelete();
            $table->decimal('shipping_label_fee', 14, 4)->nullable()->after('shipping_fee');
        });

        $now = now();

        DB::table('shipping_carriers')->insert([
            [
                'code' => 'aramex',
                'name' => json_encode(['en' => 'Aramex', 'ar' => 'أرامكس'], JSON_UNESCAPED_UNICODE),
                'fee' => null,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'ups',
                'name' => json_encode(['en' => 'UPS', 'ar' => 'يو بي إس'], JSON_UNESCAPED_UNICODE),
                'fee' => null,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $abilities = [
            'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore',
            'forceDelete', 'forceDeleteAny', 'restoreAny', 'replicate', 'reorder',
        ];
        $permissions = [];

        foreach ($abilities as $ability) {
            $permissions[] = Permission::findOrCreate(str($ability)->studly()->toString().':ShippingCarrier', 'web');
        }

        foreach (['super_admin', 'orders_manager'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->where('guard_name', 'web')->first();
            $role?->givePermissionTo($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipping_carrier_id');
            $table->dropColumn('shipping_label_fee');
        });

        Schema::dropIfExists('shipping_carriers');
    }
};
