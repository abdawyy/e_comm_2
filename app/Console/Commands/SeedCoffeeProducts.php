<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\ContentString;
use App\Models\DesignAsset;
use App\Models\HomeBanner;
use App\Models\PreparationService;
use App\Models\Product;
use App\Models\SeoSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SeedCoffeeProducts extends Command
{
    protected $signature = 'store:coffee';

    protected $description = 'Add coffee products, pictures, home wording, and SEO';

    public function handle(): int
    {
        $category = Category::query()->where('name->en', 'Drinks')->first()
            ?? Category::query()->where('name->en', 'Coffee')->first()
            ?? Category::query()->create([
                'name' => ['en' => 'Coffee', 'ar' => 'قهوة'],
                'description' => ['en' => 'Whole beans and ground coffee.', 'ar' => 'حبوب كاملة وقهوة مطحونة.'],
                'is_active' => true,
                'sort_order' => 2,
            ]);

        Storage::disk('public')->put('categories/coffee.svg', $this->shelfSvg());
        $category->update([
            'name' => ['en' => 'Coffee', 'ar' => 'قهوة'],
            'description' => ['en' => 'Whole beans and ground coffee, sold by the pack or the kilo.', 'ar' => 'حبوب كاملة وقهوة مطحونة، بالعبوة أو بالكيلو.'],
            'image_path' => 'categories/coffee.svg',
            'is_active' => true,
        ]);

        $grind = PreparationService::query()->where('name->en', 'Grind')->first();
        $rows = [];

        foreach ($this->catalog() as $item) {
            $path = 'products/'.$item['sku'].'.svg';
            Storage::disk('public')->put($path, $this->svg($item));

            $product = Product::query()->updateOrCreate(['sku' => $item['sku']], [
                'category_id' => $category->id,
                'name' => ['en' => $item['en'], 'ar' => $item['ar']],
                'description' => ['en' => $item['description_en'], 'ar' => $item['description_ar']],
                'price_per_unit' => $item['price'],
                'unit' => $item['unit'],
                'track_stock' => true,
                'stock_quantity' => $item['stock'],
                'image_path' => $path,
                'is_featured' => $item['featured'],
                'is_active' => true,
            ]);

            if ($grind && $item['grind']) {
                $product->services()->syncWithoutDetaching([$grind->id]);
            }

            $rows[] = [$product->sku, $item['en'], $item['price'].' EGP/'.$item['unit'], $path];
        }

        Product::query()
            ->whereNotIn('sku', array_column($this->catalog(), 'sku'))
            ->update(['is_featured' => false]);

        $this->table(['SKU', 'Name', 'Price', 'Image'], $rows);
        $this->publishSite();
        $this->info('Coffee products, home page, and SEO are ready.');

        return self::SUCCESS;
    }

    private function publishSite(): void
    {
        $disk = Storage::disk('public');
        $disk->put('design/hero.svg', $this->heroSvg());
        $disk->put('design/og_default.svg', $this->shareSvg());
        $disk->put('design/favicon.svg', $this->markSvg());
        $disk->put('design/footer_mark.svg', $this->markSvg());
        $disk->put('design/logo.svg', $this->markSvg());
        $disk->put('design/logo_dark.svg', $this->markSvg());
        $disk->put('banners/coffee-beans.svg', $this->bannerSvg('#3B2418', 'Whole beans', 'حبوب كاملة'));
        $disk->put('banners/coffee-cup.svg', $this->bannerSvg('#2F4F47', 'Turkish coffee', 'قهوة تركي'));

        foreach ([
            'logo' => ['design/logo.svg', 'Atlas coffee mark', 'علامة قهوة أطلس'],
            'logo_dark' => ['design/logo_dark.svg', 'Atlas coffee mark', 'علامة قهوة أطلس'],
            'hero' => ['design/hero.svg', 'A cup of Atlas coffee', 'فنجان قهوة أطلس'],
            'og_default' => ['design/og_default.svg', 'Atlas Market specialty coffee', 'قهوة أطلس المختصة'],
            'favicon' => ['design/favicon.svg', 'Atlas coffee mark', 'علامة قهوة أطلس'],
            'footer_mark' => ['design/footer_mark.svg', 'Atlas coffee mark', 'علامة قهوة أطلس'],
        ] as $key => [$path, $altEn, $altAr]) {
            DesignAsset::query()->updateOrCreate(['key' => $key], [
                'image_path' => $path,
                'alt' => ['en' => $altEn, 'ar' => $altAr],
                'is_active' => true,
            ]);
        }

        foreach ([
            'home.kicker' => ['Roasted in Cairo', 'تحميص القاهرة'],
            'home.hero_title' => ['Coffee for the neighborhood', 'قهوة بطعم الحارة'],
            'home.hero_subtitle' => ['Sidamo, Yirgacheffe, espresso, and Turkish coffee, ground the way you brew.', 'سيدامو ويرغاتشيف وإسبريسو وقهوة تركي، تُطحن على طريقة تحضيرك.'],
            'home.story_title' => ['From the sack to your cup', 'من الشوال إلى الفنجان'],
            'home.story_body' => ['Whole beans by the pack or the kilo, a grind for your pot, and delivery across Cairo, Giza, and Alexandria.', 'حبوب كاملة بالعبوة أو بالكيلو، وطحن على قدر الإبريق، وتوصيل في القاهرة والجيزة والإسكندرية.'],
            'trust.shipping' => ['Coffee delivered across Cairo, Giza, and Alexandria', 'توصيل القهوة في القاهرة والجيزة والإسكندرية'],
            'trust.payment' => ['Cash on delivery or Visa', 'الدفع عند الاستلام أو فيزا'],
            'trust.support' => ['Arabic and English, from the roast to your door', 'دعم بالعربية والإنجليزية من التحميص حتى الباب'],
            'footer.blurb' => ['Specialty coffee you can reorder from home.', 'قهوة مختصة تعيد طلبها من البيت.'],
        ] as $key => [$en, $ar]) {
            ContentString::query()->updateOrCreate(['key' => $key], ['value' => ['en' => $en, 'ar' => $ar]]);
        }

        HomeBanner::query()->updateOrCreate(['id' => 1], [
            'title' => ['en' => 'Whole beans, your grind', 'ar' => 'حبوب كاملة وطحنك'],
            'subtitle' => ['en' => 'Sidamo, Yirgacheffe, and Santos. Ask for a grind when you add them.', 'ar' => 'سيدامو ويرغاتشيف وسانتوس. اطلب الطحن عند الإضافة.'],
            'badge' => ['en' => 'Roasted', 'ar' => 'محمّص'],
            'image_path' => 'banners/coffee-beans.svg',
            'gradient_start' => '#1A2B28',
            'gradient_end' => '#C9A55C',
            'buttons' => [['label' => ['en' => 'Shop coffee', 'ar' => 'تسوق القهوة'], 'url' => '/shop']],
            'is_active' => true,
            'sort_order' => 1,
        ]);
        HomeBanner::query()->updateOrCreate(['id' => 2], [
            'title' => ['en' => 'Turkish coffee for the cezve', 'ar' => 'قهوة تركي للركوة'],
            'subtitle' => ['en' => 'Fine ground coffee, ready for the pot.', 'ar' => 'قهوة ناعمة جاهزة للركوة.'],
            'badge' => ['en' => 'House blend', 'ar' => 'خلطة الدار'],
            'image_path' => 'banners/coffee-cup.svg',
            'gradient_start' => '#0F1B19',
            'gradient_end' => '#2F4F47',
            'buttons' => [['label' => ['en' => 'See the pack', 'ar' => 'شاهد العبوة'], 'url' => '/products/turkish-coffee']],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $share = 'design/og_default.svg';
        foreach ([
            'home' => ['Specialty coffee in Cairo | Atlas Market', 'قهوة مختصة في القاهرة | سوق أطلس', 'Order Sidamo, Yirgacheffe, espresso, Turkish coffee, and decaf. Whole beans or ground, with delivery in Egypt.', 'اطلب سيدامو ويرغاتشيف والإسبريسو والقهوة التركي والمنزوعة الكافيين. حبوب كاملة أو مطحونة، مع التوصيل في مصر.'],
            'shop' => ['Shop coffee | Atlas Market', 'تسوق القهوة | سوق أطلس', 'Browse whole-bean and ground coffee sold by the pack or the kilo.', 'تصفح القهوة الحبّة والمطحونة بالعبوة أو بالكيلو.'],
            'category' => ['Coffee shelves | Atlas Market', 'أرفف القهوة | سوق أطلس', 'Specialty coffee from the Atlas Market shelves.', 'قهوة مختصة من أرفف سوق أطلس.'],
            'product' => ['Coffee | Atlas Market', 'قهوة | سوق أطلس', 'Specialty coffee from Atlas Market, ground the way you brew.', 'قهوة مختصة من سوق أطلس، تُطحن على طريقة تحضيرك.'],
            'bundles' => ['Coffee bundles | Atlas Market', 'حزم القهوة | سوق أطلس', 'Coffee bundles for the week, delivered in Egypt.', 'حزم قهوة للأسبوع، مع التوصيل في مصر.'],
        ] as $key => [$titleEn, $titleAr, $bodyEn, $bodyAr]) {
            SeoSetting::query()->updateOrCreate(['route_key' => $key], [
                'meta_title' => ['en' => $titleEn, 'ar' => $titleAr],
                'meta_description' => ['en' => $bodyEn, 'ar' => $bodyAr],
                'og_image_path' => $share,
            ]);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function catalog(): array
    {
        return [
            ['sku' => 'ATL-COFFEE', 'en' => 'Sidamo coffee', 'ar' => 'قهوة سيدامو', 'description_en' => 'Berry and cocoa whole beans from Sidamo. Sold by the pack, and we can grind them for your pot.', 'description_ar' => 'حبوب كاملة من سيدامو بنكهة التوت والكاكاو. تباع بالعبوة، ويمكن طحنها لإبريقك.', 'price' => '140.0000', 'unit' => 'pack', 'stock' => '30.0000', 'featured' => true, 'grind' => true, 'roast' => '#3B2418', 'band' => '#C9A55C'],
            ['sku' => 'ATL-YIRG', 'en' => 'Yirgacheffe beans', 'ar' => 'حبوب يرغاتشيف', 'description_en' => 'Floral Yirgacheffe whole beans, sold by the kilo for pour-over and filter.', 'description_ar' => 'حبوب يرغاتشيف الزهرية بالكيلو، للترشيح والتحضير البطيء.', 'price' => '320.0000', 'unit' => 'kg', 'stock' => '18.0000', 'featured' => true, 'grind' => true, 'roast' => '#5C3317', 'band' => '#E0C58A'],
            ['sku' => 'ATL-SANTOS', 'en' => 'Santos coffee', 'ar' => 'قهوة سانتوس', 'description_en' => 'Soft Brazilian Santos beans in a pack. A gentle daily cup with a nutty finish.', 'description_ar' => 'حبوب سانتوس البرازيلية الناعمة في عبوة. فنجان يومي خفيف بنهاية جوزية.', 'price' => '165.0000', 'unit' => 'pack', 'stock' => '24.0000', 'featured' => true, 'grind' => true, 'roast' => '#6F4E37', 'band' => '#C9A55C'],
            ['sku' => 'ATL-ESPRESSO', 'en' => 'House espresso', 'ar' => 'إسبريسو الدار', 'description_en' => 'A dark house blend for espresso and milk drinks, sold by the pack.', 'description_ar' => 'خلطة الدار الداكنة للإسبريسو ومشروبات الحليب، تباع بالعبوة.', 'price' => '155.0000', 'unit' => 'pack', 'stock' => '22.0000', 'featured' => true, 'grind' => true, 'roast' => '#1A120B', 'band' => '#2F4F47'],
            ['sku' => 'ATL-TURK', 'en' => 'Turkish coffee', 'ar' => 'قهوة تركي', 'description_en' => 'Fine ground coffee for a cezve, sold by the pack. No extra grind needed.', 'description_ar' => 'قهوة ناعمة للركوة، تباع بالعبوة. لا تحتاج طحناً إضافياً.', 'price' => '95.0000', 'unit' => 'pack', 'stock' => '40.0000', 'featured' => false, 'grind' => false, 'roast' => '#4A2C2A', 'band' => '#E0C58A'],
            ['sku' => 'ATL-DECAF', 'en' => 'Decaf blend', 'ar' => 'خلطة منزوعة الكافيين', 'description_en' => 'A mild decaf blend for the evening, sold by the kilo.', 'description_ar' => 'خلطة خفيفة منزوعة الكافيين للمساء، تباع بالكيلو.', 'price' => '280.0000', 'unit' => 'kg', 'stock' => '12.0000', 'featured' => false, 'grind' => true, 'roast' => '#8B5E3C', 'band' => '#F5EFE6'],
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function svg(array $item): string
    {
        $name = htmlspecialchars((string) $item['en'], ENT_XML1);
        $arabic = htmlspecialchars((string) $item['ar'], ENT_XML1);
        $roast = $item['roast'];
        $band = $item['band'];

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="800" viewBox="0 0 800 800">
  <rect width="800" height="800" fill="#F5EFE6"/>
  <path d="M250 170h300l40 70v390c0 28-22 50-50 50H260c-28 0-50-22-50-50V240z" fill="{$roast}"/>
  <path d="M250 170h300l40 70H210z" fill="{$band}"/>
  <rect x="300" y="300" width="200" height="150" rx="16" fill="#F5EFE6"/>
  <circle cx="360" cy="360" r="16" fill="{$roast}"/>
  <circle cx="400" cy="348" r="16" fill="{$roast}"/>
  <circle cx="440" cy="364" r="16" fill="{$roast}"/>
  <circle cx="380" cy="390" r="16" fill="{$roast}"/>
  <circle cx="424" cy="398" r="14" fill="{$roast}"/>
  <text x="400" y="500" text-anchor="middle" fill="#F5EFE6" font-size="28" font-family="Georgia, serif">{$name}</text>
  <text x="400" y="548" text-anchor="middle" fill="{$band}" font-size="26" font-family="sans-serif">{$arabic}</text>
</svg>
SVG;
    }

    private function heroSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="900" viewBox="0 0 1200 900">
  <rect width="1200" height="900" fill="#1A2B28"/>
  <rect x="80" y="80" width="1040" height="740" rx="48" fill="#2F4F47"/>
  <ellipse cx="430" cy="620" rx="180" ry="36" fill="#0F1B19"/>
  <path d="M310 430h240v150c0 66-54 120-120 120s-120-54-120-120z" fill="#F5EFE6"/>
  <path d="M550 470c70 10 90 70 40 110" fill="none" stroke="#C9A55C" stroke-width="22" stroke-linecap="round"/>
  <path d="M360 430c20-70 180-70 200 0" fill="#E0C58A"/>
  <ellipse cx="820" cy="390" rx="150" ry="90" fill="#3B2418"/>
  <circle cx="760" cy="370" r="22" fill="#C9A55C"/>
  <circle cx="820" cy="350" r="22" fill="#E0C58A"/>
  <circle cx="880" cy="380" r="22" fill="#C9A55C"/>
  <circle cx="800" cy="420" r="18" fill="#E0C58A"/>
  <circle cx="860" cy="430" r="16" fill="#C9A55C"/>
</svg>
SVG;
    }

    private function shareSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630">
  <rect width="1200" height="630" fill="#1A2B28"/>
  <rect x="70" y="70" width="360" height="490" rx="28" fill="#3B2418"/>
  <rect x="70" y="70" width="360" height="90" fill="#C9A55C"/>
  <text x="500" y="250" fill="#F5EFE6" font-size="64" font-family="Georgia, serif">Specialty coffee</text>
  <text x="500" y="340" fill="#E0C58A" font-size="48" font-family="sans-serif">قهوة مختصة</text>
  <text x="500" y="430" fill="#F5EFE6" font-size="32" font-family="sans-serif">Atlas Market · Cairo</text>
</svg>
SVG;
    }

    private function markSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">
  <rect width="128" height="128" rx="28" fill="#1A2B28"/>
  <ellipse cx="64" cy="70" rx="28" ry="18" fill="#C9A55C"/>
  <path d="M48 58c8-22 32-22 40 0" fill="#E0C58A"/>
</svg>
SVG;
    }

    private function shelfSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500" viewBox="0 0 800 500">
  <rect width="800" height="500" fill="#F5EFE6"/>
  <rect x="70" y="80" width="150" height="280" rx="16" fill="#3B2418"/>
  <rect x="250" y="110" width="150" height="250" rx="16" fill="#5C3317"/>
  <rect x="430" y="70" width="150" height="290" rx="16" fill="#1A120B"/>
  <rect x="610" y="130" width="120" height="230" rx="16" fill="#6F4E37"/>
  <rect x="70" y="80" width="150" height="48" fill="#C9A55C"/>
  <rect x="250" y="110" width="150" height="48" fill="#E0C58A"/>
  <rect x="430" y="70" width="150" height="48" fill="#2F4F47"/>
  <rect x="610" y="130" width="120" height="40" fill="#C9A55C"/>
</svg>
SVG;
    }

    private function bannerSvg(string $color, string $label, string $arabic): string
    {
        $label = htmlspecialchars($label, ENT_XML1);
        $arabic = htmlspecialchars($arabic, ENT_XML1);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="500" viewBox="0 0 1200 500">
  <rect width="1200" height="500" fill="{$color}"/>
  <circle cx="280" cy="250" r="90" fill="#C9A55C"/>
  <circle cx="250" cy="230" r="18" fill="#1A2B28"/>
  <circle cx="300" cy="220" r="18" fill="#1A2B28"/>
  <circle cx="320" cy="270" r="16" fill="#1A2B28"/>
  <text x="460" y="230" fill="#F5EFE6" font-size="54" font-family="Georgia, serif">{$label}</text>
  <text x="460" y="310" fill="#E0C58A" font-size="42" font-family="sans-serif">{$arabic}</text>
</svg>
SVG;
    }
}
