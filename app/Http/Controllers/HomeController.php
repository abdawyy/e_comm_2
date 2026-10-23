<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('store.home', [
            'categories' => $this->safe(fn () => Category::query()->where('is_active', true)->orderBy('sort_order')->limit(8)->get()),
            'featured' => $this->safe(fn () => Product::query()->where('is_active', true)->where('is_featured', true)->limit(8)->get()),
            'banners' => $this->safe(fn () => Schema::hasTable('home_banners') ? HomeBanner::query()->scheduled()->get() : collect()),
        ]);
    }

    private function safe(callable $callback)
    {
        try {
            return $callback();
        } catch (Throwable) {
            return collect();
        }
    }
}
