<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_internal')->default(false)->after('is_active');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('mail_status')->default('pending')->after('confirmation_sent_at');
            $table->text('mail_error')->nullable()->after('mail_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_internal');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['mail_status', 'mail_error']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
};
