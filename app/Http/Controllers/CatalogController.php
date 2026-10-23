<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function shop(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'images'])
            ->where('is_active', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name->en', 'like', $term)
                        ->orWhere('name->ar', 'like', $term)
                        ->orWhere('sku', 'like', $term);
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->filled('unit'), fn ($query) => $query->where('unit', $request->string('unit')))
            ->when($request->boolean('stock'), fn ($query) => $query->where(function ($inner) {
                $inner->where('track_stock', false)->orWhere('stock_quantity', '>', 0);
            }))
            ->orderBy('name->en')
            ->paginate(12)
            ->withQueryString();

        return view('store.shop', [
            'products' => $products,
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function category(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $products = $category->products()->where('is_active', true)->paginate(12);

        return view('store.category', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);
        $product->load(['images', 'services', 'packagingTypes', 'category']);

        $related = Product::query()
            ->with(['category', 'images'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->limit(4)
            ->get();
        $sameAisle = $related->isNotEmpty();

        if (! $sameAisle) {
            $related = Product::query()
                ->with(['category', 'images'])
                ->where('is_active', true)
                ->whereKeyNot($product->id)
                ->limit(4)
                ->get();
        }

        return view('store.product', [
            'product' => $product,
            'related' => $related,
            'relatedTitle' => $sameAisle ? __('store.product.related') : __('store.product.also'),
        ]);
    }

    public function bundles(): View
    {
        return view('store.bundles', [
            'bundles' => Bundle::query()->where('is_active', true)->latest()->get(),
        ]);
    }

    public function bundle(Bundle $bundle): View
    {
        abort_unless($bundle->is_active, 404);
        $bundle->load('items.product');

        return view('store.bundle', [
            'bundle' => $bundle,
            'cities' => \App\Models\City::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
