<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->decimal('shipping_fee', 14, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->index();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->text('address')->nullable();
            $table->boolean('is_admin')->default(false);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku')->unique();
            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->decimal('price_per_unit', 14, 4);
            $table->string('unit')->default('piece');
            $table->string('secondary_unit')->nullable();
            $table->decimal('secondary_price', 14, 4)->nullable();
            $table->boolean('track_stock')->default(true);
            $table->decimal('stock_quantity', 14, 4)->default(0);
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('preparation_services', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('surcharge_type')->default('fixed');
            $table->decimal('surcharge_value', 14, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_preparation_service', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('preparation_service_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'preparation_service_id']);
        });

        Schema::create('packaging_types', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('surcharge_type')->default('fixed');
            $table->decimal('surcharge_value', 14, 4)->default(0);
            $table->string('scope')->default('line');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_packaging_type', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('packaging_type_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'packaging_type_id']);
        });

        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->decimal('price', 14, 4);
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_subscribable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 14, 4)->default(1);
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type');
            $table->decimal('value', 14, 4);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->decimal('min_subtotal', 14, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('status')->default('pending')->index();
            $table->string('payment_status')->default('unpaid')->index();
            $table->string('payment_gateway');
            $table->string('currency', 8)->default('EGP');
            $table->string('locale', 5)->default('en');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('city_name')->nullable();
            $table->text('address');
            $table->decimal('subtotal', 14, 4);
            $table->decimal('discount', 14, 4)->default(0);
            $table->string('coupon_code')->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('order_packaging_fee', 14, 4)->default(0);
            $table->json('order_packaging_snapshot')->nullable();
            $table->decimal('shipping_fee', 14, 4)->default(0);
            $table->decimal('total', 14, 4);
            $table->unsignedBigInteger('amount_cents')->nullable();
            $table->text('notes')->nullable();
            $table->string('invoice_path')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('confirmation_sent_at')->nullable();
            $table->string('paymob_transaction_id')->nullable();
            $table->string('masked_pan')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('kind');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('bundle_id')->nullable()->constrained()->nullOnDelete();
            $table->json('name');
            $table->string('unit')->nullable();
            $table->decimal('unit_price', 14, 4);
            $table->decimal('quantity', 14, 4);
            $table->json('services_snapshot')->nullable();
            $table->json('packaging_snapshot')->nullable();
            $table->decimal('line_base', 14, 4);
            $table->decimal('services_fee', 14, 4)->default(0);
            $table->decimal('packaging_fee', 14, 4)->default(0);
            $table->decimal('line_subtotal', 14, 4);
            $table->timestamps();
        });

        Schema::create('order_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->string('type')->default('note');
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 14, 4)->default(1);
            $table->string('interval');
            $table->string('status')->default('active');
            $table->date('next_order_at');
            $table->string('payment_gateway')->default('cod');
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->text('address');
            $table->string('phone');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->timestamps();
        });

        Schema::create('content_strings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->timestamps();
        });

        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('route_key')->unique();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->string('og_image_path')->nullable();
            $table->timestamps();
        });

        Schema::create('home_banners', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('subtitle')->nullable();
            $table->json('badge')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_url')->nullable();
            $table->string('gradient_start')->nullable();
            $table->string('gradient_end')->nullable();
            $table->json('buttons')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('design_assets', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('image_path')->nullable();
            $table->json('alt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_themes', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('primary', 7);
            $table->string('primary_dark', 7);
            $table->string('primary_light', 7);
            $table->string('accent', 7);
            $table->string('accent_light', 7);
            $table->string('canvas', 7);
            $table->string('surface', 7);
            $table->string('border', 7);
            $table->string('success', 7);
            $table->string('danger', 7);
            $table->string('theme_color', 7);
            $table->timestamps();
        });

        Schema::create('site_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->string('device_type')->nullable();
            $table->text('referrer')->nullable();
            $table->string('referrer_host')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('country_code', 8)->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->timestamp('geo_resolved_at')->nullable();
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at')->index();
            $table->timestamps();
        });

        Schema::create('site_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_visitor_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->timestamp('viewed_at')->index();
        });

        Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamp('visited_at')->index();
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('gateway');
            $table->string('intention_id')->nullable();
            $table->text('client_secret')->nullable();
            $table->string('paymob_order_id')->nullable()->index();
            $table->string('paymob_transaction_id')->nullable()->unique();
            $table->string('special_reference')->index();
            $table->unsignedBigInteger('amount_cents');
            $table->string('status')->default('pending');
            $table->string('masked_pan')->nullable();
            $table->json('payload')->nullable();
            $table->boolean('hmac_valid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('phone_otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->index();
            $table->string('code_hash');
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_otps');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('product_views');
        Schema::dropIfExists('site_page_views');
        Schema::dropIfExists('site_visitors');
        Schema::dropIfExists('site_themes');
        Schema::dropIfExists('design_assets');
        Schema::dropIfExists('home_banners');
        Schema::dropIfExists('seo_settings');
        Schema::dropIfExists('content_strings');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('order_notes');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('bundle_items');
        Schema::dropIfExists('bundles');
        Schema::dropIfExists('product_packaging_type');
        Schema::dropIfExists('packaging_types');
        Schema::dropIfExists('product_preparation_service');
        Schema::dropIfExists('preparation_services');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('city_id');
            $table->dropColumn(['phone', 'address', 'is_admin']);
        });
        Schema::dropIfExists('cities');
    }
};
