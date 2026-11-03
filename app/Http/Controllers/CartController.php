<?php

namespace App\Http\Controllers;

use App\DTO\CartQuote;
use App\Exceptions\CartException;
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
            return $this->fail($request, $exception);
        }

        return $this->respond($request, $quote, __('store.cart.added'));
    }

    public function update(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required']]);

        try {
            $quote = $this->cart->update($key, $data['quantity']);
        } catch (CartException $exception) {
            return $this->fail($request, $exception);
        }

        return $this->respond($request, $quote, __('store.cart.updated'));
    }

    public function destroy(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $quote = $this->cart->remove($key);

        return $this->respond($request, $quote, __('store.cart.removed'));
    }

    public function coupon(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:64']]);

        try {
            $quote = $this->cart->applyCoupon($data['code']);
        } catch (CartException $exception) {
            return $this->fail($request, $exception);
        }

        return $this->respond($request, $quote, __('store.cart.coupon_applied'));
    }

    public function forgetCoupon(Request $request): JsonResponse|RedirectResponse
    {
        $this->cart->clearCoupon();

        return $this->respond($request, $this->cart->quote(), __('store.cart.coupon_removed'));
    }

    private function respond(Request $request, CartQuote $quote, string $message): JsonResponse|RedirectResponse
    {
        return $request->expectsJson()
            ? response()->json([
                'ok' => true,
                'message' => $message,
                'count' => $quote->count(),
                'lines' => count($quote->lines),
                'quote' => $quote->toArray(),
            ])
            : redirect()->route('cart.show')->with('status', $message);
    }

    private function fail(Request $request, CartException $exception): JsonResponse|RedirectResponse
    {
        $message = __($exception->errorKey);

        return $request->expectsJson()
            ? response()->json(['message' => $message], 422)
            : back()->with('error', $message);
    }
}
