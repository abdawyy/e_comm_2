<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_inquiry')->default(false)->after('is_internal');
        });

        Schema::table('product_inquiries', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
        });

        $categoryIds = Category::query()->where('is_inquiry', true)->pluck('id');

        if ($categoryIds->isNotEmpty()) {
            Product::query()->whereIn('category_id', $categoryIds)->update(['is_inquiry' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('product_inquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_inquiry');
        });
    }
};
