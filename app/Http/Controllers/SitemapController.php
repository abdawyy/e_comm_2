<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            $this->entry(route('home')),
            $this->entry(route('shop')),
            $this->entry(route('bundles.index')),
        ];

        if (Schema::hasTable('categories')) {
            foreach (Category::query()->where('is_active', true)->get() as $category) {
                $urls[] = $this->entry(route('categories.show', $category), $category->updated_at);
            }
        }

        if (Schema::hasTable('products')) {
            foreach (Product::query()->where('is_active', true)->where('is_internal', false)->get() as $product) {
                $urls[] = $this->entry(route('products.show', $product), $product->updated_at);
            }
        }

        if (Schema::hasTable('bundles')) {
            foreach (Bundle::query()->where('is_active', true)->get() as $bundle) {
                $urls[] = $this->entry(route('bundles.show', $bundle), $bundle->updated_at);
            }
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $body = "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * @return array{loc: string, lastmod: ?string, ar: string, en: string}
     */
    private function entry(string $url, mixed $updated = null): array
    {
        $base = strtok($url, '?') ?: $url;

        return [
            'loc' => $base.'?locale=ar',
            'lastmod' => $updated ? $updated->toAtomString() : null,
            'ar' => $base.'?locale=ar',
            'en' => $base.'?locale=en',
        ];
    }
}
