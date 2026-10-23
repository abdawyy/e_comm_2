<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_visitors', function (Blueprint $table) {
            $table->string('area')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('site_visitors', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }
};
