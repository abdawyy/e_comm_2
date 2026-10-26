<?php

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
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform')->unique();
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $links = [
            ['facebook', 'https://facebook.com'],
            ['instagram', 'https://instagram.com'],
            ['tiktok', 'https://www.tiktok.com'],
            ['youtube', 'https://youtube.com'],
            ['whatsapp', 'https://whatsapp.com'],
            ['x', 'https://x.com'],
        ];

        foreach ($links as $index => [$platform, $url]) {
            \Illuminate\Support\Facades\DB::table('social_links')->insert([
                'platform' => $platform,
                'url' => $url,
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $abilities = [
            'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore',
            'forceDelete', 'forceDeleteAny', 'restoreAny', 'replicate', 'reorder',
        ];
        $permissions = [];

        foreach ($abilities as $ability) {
            $permissions[] = Permission::findOrCreate(str($ability)->studly()->toString().':SocialLink', 'web');
        }

        foreach (['super_admin', 'design_manager', 'content_manager'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->where('guard_name', 'web')->first();
            $role?->givePermissionTo($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
