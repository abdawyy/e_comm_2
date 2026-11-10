<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('order_email');
        });

        if (Schema::hasTable('site_themes')) {
            DB::table('site_themes')->update([
                'canvas' => '#FFFFFF',
                'surface' => '#FFFFFF',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
        });
    }
};
