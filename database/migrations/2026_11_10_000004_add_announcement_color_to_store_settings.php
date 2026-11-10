<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->string('announcement_color', 7)->default('#eeddb0')->after('announcement_ar');
            $table->unsignedTinyInteger('announcement_opacity')->default(100)->after('announcement_color');
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn(['announcement_color', 'announcement_opacity']);
        });
    }
};
