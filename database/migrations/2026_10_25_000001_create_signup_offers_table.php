<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signup_offers', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('delay_seconds')->default(15);
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('heading_en');
            $table->string('heading_ar');
            $table->text('body_en');
            $table->text('body_ar');
            $table->timestamps();
        });

        Schema::create('signup_leads', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('locale', 5)->default('en');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->unique(['email', 'coupon_id']);
        });

        $couponId = DB::table('coupons')->where('code', 'WELCOME10')->value('id');

        DB::table('signup_offers')->insert([
            'is_active' => true,
            'delay_seconds' => 15,
            'coupon_id' => $couponId,
            'heading_en' => 'Sign up and save',
            'heading_ar' => 'سجل واحصل على خصم',
            'body_en' => 'Leave your email and we will send your discount coupon.',
            'body_ar' => 'اترك بريدك وسنرسل كوبون الخصم إلى بريدك.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('signup_leads');
        Schema::dropIfExists('signup_offers');
    }
};
