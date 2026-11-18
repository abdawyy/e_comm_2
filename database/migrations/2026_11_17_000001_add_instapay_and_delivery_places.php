<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->string('instapay_number')->nullable();
            $table->boolean('instapay_active')->default(true);
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('key')->unique();
            $table->json('name');
            $table->decimal('shipping_fee', 14, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('area_id')->nullable()->after('city_name')->constrained('areas')->nullOnDelete();
            $table->string('area_name')->nullable()->after('area_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('area_id');
            $table->dropColumn('area_name');
        });

        Schema::dropIfExists('areas');

        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn(['instapay_number', 'instapay_active']);
        });
    }
};
