<?php

namespace App\Http\Controllers;

use App\Exceptions\CartException;
use App\Models\Coupon;
use App\Support\SessionCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private SessionCart $cart) {}

    public function show(): mixed
    {
        return view('store.cart', [
            'quote' => $this->cart->quote(),
        ]);
    }

    public function summary(): JsonResponse
    {
        $quote = $this->cart->quote();

        return response()->json([
            'count' => $quote->count(),
            'quote' => $quote->toArray(),
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'integer', 'required_without:bundle_id'],
            'bundle_id' => ['nullable', 'integer', 'required_without:product_id'],
            'quantity' => ['required'],
            'unit' => ['nullable', 'string', 'max:32'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer'],
            'packaging_id' => ['nullable', 'integer'],
        ]);

        try {
            $quote = $this->cart->add($data);
        } catch (CartException $exception) {
            return response()->json(['message' => __($exception->errorKey)], 422);
        }

        return $request->expectsJson()
            ? response()->json([
                'ok' => true,
                'message' => __('store.cart.added'),
                'count' => $quote->count(),
                'lines' => count($quote->lines),
                'quote' => $quote->toArray(),
            ])
            : redirect()->route('cart.show')->with('status', __('store.cart.added'));
    }

    public function update(Request $request, string $key): JsonResponse
    {
        $data = $request->validate(['quantity' => ['required']]);

        try {
            $quote = $this->cart->update($key, $data['quantity']);
        } catch (CartException $exception) {
            return response()->json(['message' => __($exception->errorKey)], 422);
        }

        return response()->json(['ok' => true, 'count' => $quote->count(), 'quote' => $quote->toArray()]);
    }

    public function destroy(string $key): JsonResponse
    {
        $quote = $this->cart->remove($key);

        return response()->json(['ok' => true, 'count' => $quote->count(), 'quote' => $quote->toArray()]);
    }

    public function coupon(Request $request): JsonResponse
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:64']]);

        try {
            $quote = $this->cart->applyCoupon($data['code']);
        } catch (CartException $exception) {
            return response()->json(['message' => __($exception->errorKey)], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => __('store.cart.coupon_applied'),
            'quote' => $quote->toArray(),
        ]);
    }

    public function forgetCoupon(): JsonResponse
    {
        $this->cart->clearCoupon();

        return response()->json(['ok' => true, 'quote' => $this->cart->quote()->toArray()]);
    }
}
