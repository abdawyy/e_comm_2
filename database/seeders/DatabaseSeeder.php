<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\City;
use App\Models\ContentString;
use App\Models\Coupon;
use App\Models\DesignAsset;
use App\Models\HomeBanner;
use App\Models\PackagingType;
use App\Models\PreparationService;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SeoSetting;
use App\Models\SiteTheme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminRolesSeeder::class);

        $theme = SiteTheme::query()->first() ?? new SiteTheme;
        $theme->fill(array_merge(config('store.theme_defaults'), ['brand_name' => 'Atlas Market']));
        $theme->save();

        $this->seedAssets();
        $this->seedCatalog();
    }

    private function seedAssets(): void
    {
        $colors = [
            'logo' => '#1A2B28',
            'logo_dark' => '#2F4F47',
            'favicon' => '#1A2B28',
            'hero' => '#1A2B28',
            'og_default' => '#2F4F47',
            'empty_cart' => '#C9A55C',
            'empty_shop' => '#2F4F47',
            'auth_side' => '#0F1B19',
            'footer_mark' => '#C9A55C',
        ];

        foreach (DesignAsset::KEYS as $key) {
            $path = 'design/'.$key.'.svg';

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $this->svg($colors[$key] ?? '#1A2B28'));
            }

            DesignAsset::query()->updateOrCreate(['key' => $key], [
                'image_path' => $path,
                'alt' => [
                    'en' => trans('admin.design.'.$key, [], 'en'),
                    'ar' => trans('admin.design.'.$key, [], 'ar'),
                ],
                'is_active' => true,
            ]);
        }
    }

    private function svg(string $color): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="640" height="360" viewBox="0 0 640 360"><rect width="640" height="360" rx="28" fill="'.$color.'"/></svg>';
    }

    private function seedCatalog(): void
    {
        $cairo = City::query()->updateOrCreate(['id' => 1], ['name' => ['en' => 'Cairo', 'ar' => 'القاهرة'], 'shipping_fee' => '35.0000', 'is_active' => true, 'sort_order' => 1]);
        City::query()->updateOrCreate(['id' => 2], ['name' => ['en' => 'Giza', 'ar' => 'الجيزة'], 'shipping_fee' => '40.0000', 'is_active' => true, 'sort_order' => 2]);
        City::query()->updateOrCreate(['id' => 3], ['name' => ['en' => 'Alexandria', 'ar' => 'الإسكندرية'], 'shipping_fee' => '65.0000', 'is_active' => true, 'sort_order' => 3]);

        $pantry = Category::query()->updateOrCreate(['id' => 1], ['name' => ['en' => 'Pantry', 'ar' => 'المؤن'], 'description' => ['en' => 'Staples for the week', 'ar' => 'أساسيات الأسبوع'], 'is_active' => true, 'sort_order' => 1]);
        $drinks = Category::query()->updateOrCreate(['id' => 2], ['name' => ['en' => 'Drinks', 'ar' => 'مشروبات'], 'description' => ['en' => 'Coffee and tea', 'ar' => 'قهوة وشاي'], 'is_active' => true, 'sort_order' => 2]);

        $grind = PreparationService::query()->updateOrCreate(['id' => 1], ['name' => ['en' => 'Grind', 'ar' => 'طحن'], 'surcharge_type' => 'percent', 'surcharge_value' => '5.0000', 'is_active' => true]);
        $note = PreparationService::query()->updateOrCreate(['id' => 2], ['name' => ['en' => 'Gift note', 'ar' => 'بطاقة إهداء'], 'surcharge_type' => 'fixed', 'surcharge_value' => '15.0000', 'is_active' => true]);
        $jar = PackagingType::query()->updateOrCreate(['id' => 1], ['name' => ['en' => 'Glass jar', 'ar' => 'برطمان زجاج'], 'surcharge_type' => 'fixed', 'surcharge_value' => '12.0000', 'scope' => 'line', 'is_active' => true]);
        PackagingType::query()->updateOrCreate(['id' => 2], ['name' => ['en' => 'Gift box', 'ar' => 'علبة هدية'], 'surcharge_type' => 'fixed', 'surcharge_value' => '25.0000', 'scope' => 'order', 'is_active' => true]);

        $oil = Product::query()->updateOrCreate(['sku' => 'ATL-OIL'], [
            'category_id' => $pantry->id,
            'name' => ['en' => 'Atlas olive oil', 'ar' => 'زيت زيتون أطلس'],
            'description' => ['en' => 'Cold pressed, sold by the kilo.', 'ar' => 'معصور على البارد ويباع بالكيلو.'],
            'price_per_unit' => '180.0000',
            'unit' => 'kg',
            'track_stock' => true,
            'stock_quantity' => '40.0000',
            'is_featured' => true,
            'is_active' => true,
        ]);
        $dates = Product::query()->updateOrCreate(['sku' => 'ATL-DATE'], [
            'category_id' => $pantry->id,
            'name' => ['en' => 'Medjool dates', 'ar' => 'بلح مجهول'],
            'description' => ['en' => 'Soft dates by the kilo.', 'ar' => 'بلح طري بالكيلو.'],
            'price_per_unit' => '95.0000',
            'unit' => 'kg',
            'track_stock' => true,
            'stock_quantity' => '25.0000',
            'is_featured' => true,
            'is_active' => true,
        ]);
        $coffee = Product::query()->updateOrCreate(['sku' => 'ATL-COFFEE'], [
            'category_id' => $drinks->id,
            'name' => ['en' => 'Sidamo coffee', 'ar' => 'قهوة سيدامو'],
            'description' => ['en' => 'Whole beans in a pack.', 'ar' => 'حبوب كاملة في عبوة.'],
            'price_per_unit' => '140.0000',
            'unit' => 'pack',
            'track_stock' => true,
            'stock_quantity' => '30.0000',
            'is_featured' => true,
            'is_active' => true,
        ]);

        $oil->services()->sync([$note->id]);
        $oil->packagingTypes()->sync([$jar->id]);
        $coffee->services()->sync([$grind->id]);

        $bundle = Bundle::query()->updateOrCreate(['id' => 1], [
            'name' => ['en' => 'Weekend breakfast box', 'ar' => 'صندوق إفطار نهاية الأسبوع'],
            'description' => ['en' => 'Oil, dates, and coffee.', 'ar' => 'زيت وبلح وقهوة.'],
            'price' => '390.0000',
            'is_subscribable' => true,
            'is_active' => true,
        ]);
        $bundle->items()->delete();
        $bundle->items()->createMany([
            ['product_id' => $oil->id, 'quantity' => '0.5000'],
            ['product_id' => $dates->id, 'quantity' => '0.5000'],
            ['product_id' => $coffee->id, 'quantity' => '1.0000'],
        ]);

        Coupon::query()->updateOrCreate(['code' => 'WELCOME10'], [
            'type' => 'percent',
            'value' => '10.0000',
            'usage_limit' => 100,
            'is_active' => true,
        ]);

        $welcome = Coupon::query()->where('code', 'WELCOME10')->first();
        \App\Models\SignupOffer::query()->updateOrCreate(['id' => 1], [
            'is_active' => true,
            'delay_seconds' => 15,
            'coupon_id' => $welcome?->id,
            'heading_en' => 'Sign up and save',
            'heading_ar' => 'سجل واحصل على خصم',
            'body_en' => 'Leave your email and we will send your discount coupon.',
            'body_ar' => 'اترك بريدك وسنرسل كوبون الخصم إلى بريدك.',
        ]);

        ContentString::query()->updateOrCreate(['key' => 'home.hero_title'], ['value' => ['en' => 'Groceries with a neighborhood feel', 'ar' => 'بقالة بطعم الحارة']]);
        ContentString::query()->updateOrCreate(['key' => 'home.hero_subtitle'], ['value' => ['en' => 'Olive oil, dates, coffee, and household staples.', 'ar' => 'زيت زيتون وبلح وقهوة ومستلزمات البيت.']]);
        SeoSetting::query()->updateOrCreate(['route_key' => 'home'], [
            'meta_title' => ['en' => 'Atlas Market', 'ar' => 'سوق أطلس'],
            'meta_description' => ['en' => 'Egyptian specialty grocery.', 'ar' => 'بقالة متخصصة في مصر.'],
        ]);
        HomeBanner::query()->updateOrCreate(['id' => 1], [
            'title' => ['en' => 'Weekend box', 'ar' => 'صندوق نهاية الأسبوع'],
            'subtitle' => ['en' => 'Subscribe weekly or monthly. Renewals are cash on delivery.', 'ar' => 'اشتراك أسبوعي أو شهري. التجديد عند الاستلام.'],
            'badge' => ['en' => 'New', 'ar' => 'جديد'],
            'gradient_start' => '#14532d',
            'gradient_end' => '#f97316',
            'buttons' => [['label' => ['en' => 'View bundle', 'ar' => 'عرض الحزمة'], 'url' => '/bundles']],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->seedDepartments();
        $this->seedReviews();

        $cairo->id;
    }

    private function seedDepartments(): void
    {
        $rows = [
            [10, 'Turkish coffee', 'القهوة التركي', 'https://files.easy-orders.net/1747404331498686274.jpg'],
            [11, 'Espresso', 'الإسبريسو', 'https://files.easy-orders.net/1747406902995715463.jpg'],
            [12, 'Coffee sachets', 'أظرف القهوة', 'https://files.easy-orders.net/1772048269810781313.jpg'],
            [13, 'Coffee beans', 'حبوب القهوة', 'https://files.easy-orders.net/1747404748060403340.jpg'],
            [14, 'Specialty coffee', 'القهوة المختصة', 'https://files.easy-orders.net/1747407182010509082.jpg'],
            [15, 'Coffee tools', 'أدوات القهوة', 'https://files.easy-orders.net/1763760857960912789.jpg'],
        ];

        foreach ($rows as [$sort, $en, $ar, $icon]) {
            $category = Category::query()->get()->first(fn (Category $item): bool => $item->getTranslation('name', 'en') === $en) ?? new Category;
            $category->name = ['en' => $en, 'ar' => $ar];
            $category->description = ['en' => $en, 'ar' => $ar];
            $category->is_active = true;
            $category->sort_order = $sort;
            if (blank($category->icon_path) || str_ends_with((string) $category->icon_path, '.svg') || str_starts_with((string) $category->icon_path, 'http')) {
                $category->icon_path = $icon;
            }
            $category->save();
        }
    }

    private function departmentIcon(string $letter): string
    {
        return '';
    }

    private function seedReviews(): void
    {
        $notes = [
            ['Nour', 5, 'تحميص متوازن وريحة واضحة من أول كوب.'],
            ['Omar', 4, 'الطعم ثابت والعبوة وصلت سليمة.'],
            ['Mariam', 5, 'مناسب للتحضير اليومي وطعمه نظيف.'],
        ];

        foreach (Product::query()->where('is_active', true)->get() as $product) {
            if ($product->reviews()->exists()) {
                continue;
            }

            foreach ($notes as $note) {
                ProductReview::query()->create([
                    'product_id' => $product->id,
                    'author_name' => $note[0],
                    'rating' => $note[1],
                    'body' => $note[2],
                    'is_approved' => true,
                ]);
            }
        }
    }
}
