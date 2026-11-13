<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $data = $request->validate([
            'author_name' => ['nullable', 'string', 'max:80'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $name = trim((string) ($data['author_name'] ?? ''));
        if ($request->user()) {
            $name = $name !== '' ? $name : (string) $request->user()->name;
        }

        abort_if($name === '', 422);

        ProductReview::query()->create([
            'product_id' => $product->id,
            'user_id' => $request->user()?->id,
            'author_name' => $name,
            'rating' => $data['rating'],
            'body' => trim($data['body']),
            'is_approved' => false,
        ]);

        return back()->with('status', __('store.product.review_sent'));
    }
}
