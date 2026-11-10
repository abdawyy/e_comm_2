<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->boolean('announcement_active')->default(false)->after('whatsapp_number');
            $table->string('announcement_en')->nullable()->after('announcement_active');
            $table->string('announcement_ar')->nullable()->after('announcement_en');
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn(['announcement_active', 'announcement_en', 'announcement_ar']);
        });
    }
};
