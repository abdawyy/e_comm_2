<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('building_number', 32)->nullable()->after('address');
            $table->string('floor', 16)->nullable()->after('building_number');
            $table->string('landmark', 190)->nullable()->after('floor');
            $table->decimal('latitude', 10, 7)->nullable()->after('landmark');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['building_number', 'floor', 'landmark', 'latitude', 'longitude']);
        });
    }
};
