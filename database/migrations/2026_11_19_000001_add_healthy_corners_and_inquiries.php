<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_inquiry')->default(false)->after('is_active');
        });

        Schema::create('product_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        $healthy = Category::query()->get()->first(fn (Category $item): bool => $item->getTranslation('name', 'en') === 'Healthy corners') ?? new Category;
        $healthy->name = ['en' => 'Healthy corners', 'ar' => 'زوايا صحية'];
        $healthy->description = ['en' => 'Nuts, dates, and light bites.', 'ar' => 'مكسرات وبلح ووجبات خفيفة.'];
        $healthy->is_active = true;
        $healthy->is_inquiry = false;
        $healthy->sort_order = 16;
        $healthy->icon_path = 'categories/icons/healthy.jpg';
        $healthy->image_path = $healthy->image_path ?: 'categories/icons/healthy.jpg';
        $healthy->save();

        foreach (Category::query()->get() as $category) {
            if ($category->getTranslation('name', 'en') === 'Coffee tools') {
                $category->forceFill(['is_inquiry' => true])->save();
            }
        }

        Product::query()->updateOrCreate(['sku' => 'ATL-NUTS'], [
            'category_id' => $healthy->id,
            'name' => ['en' => 'Mixed nuts bowl', 'ar' => 'طبق مكسرات مشكلة'],
            'description' => ['en' => 'Almonds, pistachios, and cashews.', 'ar' => 'لوز وفستق وكاجو.'],
            'price_per_unit' => '220.0000',
            'unit' => 'kg',
            'track_stock' => true,
            'stock_quantity' => '18.0000',
            'image_path' => 'categories/icons/healthy.jpg',
            'is_featured' => true,
            'is_active' => true,
        ]);
        Product::query()->updateOrCreate(['sku' => 'ATL-DATENUT'], [
            'category_id' => $healthy->id,
            'name' => ['en' => 'Dates and almonds', 'ar' => 'بلح ولوز'],
            'description' => ['en' => 'A small healthy corner for the week.', 'ar' => 'زاوية صحية صغيرة للأسبوع.'],
            'price_per_unit' => '160.0000',
            'unit' => 'pack',
            'track_stock' => true,
            'stock_quantity' => '20.0000',
            'image_path' => 'categories/icons/healthy.jpg',
            'is_featured' => false,
            'is_active' => true,
        ]);

        if (Schema::hasTable('permissions')) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $names = [];

            foreach (['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore', 'forceDelete', 'forceDeleteAny', 'restoreAny', 'replicate', 'reorder'] as $ability) {
                $name = str($ability)->studly()->toString().':ProductInquiry';
                Permission::findOrCreate($name, 'web');
                $names[] = $name;
            }

            foreach (['super_admin', 'orders_manager'] as $roleName) {
                $role = Role::query()->where('name', $roleName)->where('guard_name', 'web')->first();
                $role?->givePermissionTo($names);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_inquiries');

        if (Schema::hasColumn('categories', 'is_inquiry')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('is_inquiry');
            });
        }
    }
};
