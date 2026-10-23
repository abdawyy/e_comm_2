<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\Events\OrderCreated;
use App\Exceptions\CartException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\PricingService;
use App\Support\Phone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreApiController extends Controller
{
    public function categories(): JsonResponse
    {
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->getTranslations('name'),
                'slug' => $category->getTranslations('slug'),
                'image' => $category->imageUrl(),
            ]);

        return response()->json(['data' => $categories]);
    }

    public function products(Request $request): JsonResponse
    {
        $products = Product::query()->where('is_active', true)
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->paginate(20);

        return response()->json($products->through(fn (Product $product) => $this->productPayload($product)));
    }

    public function product(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);
        $product->load(['images', 'services', 'packagingTypes']);

        return response()->json(['data' => $this->productPayload($product, true)]);
    }

    public function quote(Request $request, PricingService $pricing): JsonResponse
    {
        $data = $request->validate([
            'lines' => ['required', 'array', 'min:1'],
            'coupon_code' => ['nullable', 'string'],
            'city_id' => ['nullable', 'integer'],
            'order_packaging_id' => ['nullable', 'integer'],
        ]);

        $quote = $pricing->quote($data['lines'], $data['coupon_code'] ?? null, $data['city_id'] ?? null, $data['order_packaging_id'] ?? null);

        return response()->json($quote->toArray());
    }

    public function storeOrder(Request $request, CreateOrderAction $action, PaymentGatewayManager $gateways): JsonResponse
    {
        $enabled = array_map(fn ($gateway) => $gateway->id(), $gateways->enabled());
        $data = $request->validate([
            'lines' => ['required', 'array', 'min:1'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email'],
            'customer_phone' => ['required', 'string'],
            'city_id' => ['required', 'integer'],
            'address' => ['required', 'string'],
            'payment_gateway' => ['required', 'in:'.implode(',', $enabled)],
            'coupon_code' => ['nullable', 'string'],
            'order_packaging_id' => ['nullable', 'integer'],
        ]);

        try {
            $order = $action->execute(new CreateOrderPayload(
                userId: $request->user()?->id,
                customerName: $data['customer_name'],
                customerEmail: $data['customer_email'] ?? null,
                customerPhone: Phone::normalize($data['customer_phone']),
                cityId: (int) $data['city_id'],
                address: $data['address'],
                paymentGateway: $data['payment_gateway'],
                lines: $data['lines'],
                couponCode: $data['coupon_code'] ?? null,
                orderPackagingId: $data['order_packaging_id'] ?? null,
                locale: app()->getLocale(),
            ));
        } catch (CartException $exception) {
            return response()->json(['message' => __($exception->errorKey)], 422);
        }

        $redirect = null;

        if ($order->payment_gateway === 'paymob_card') {
            $redirect = $gateways->get('paymob_card')->initiate($order)->redirectUrl;
        } else {
            event(new OrderCreated($order));
        }

        if ($order->payment_gateway === 'paymob_card') {
            event(new OrderCreated($order));
        }

        return response()->json([
            'reference' => $order->reference,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'total' => (string) $order->total,
            'amount_cents' => $order->amount_cents,
            'redirect_url' => $redirect,
        ], 201);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()->only(['id', 'name', 'email', 'phone'])]);
    }

    public function orders(Request $request): JsonResponse
    {
        $orders = Order::query()->where('user_id', $request->user()->id)->latest()->paginate(20);

        return response()->json($orders);
    }

    /**
     * @return array<string, mixed>
     */
    private function productPayload(Product $product, bool $detail = false): array
    {
        $payload = [
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->getTranslations('name'),
            'slug' => $product->getTranslations('slug'),
            'price_per_unit' => (string) $product->price_per_unit,
            'unit' => $product->unit,
            'secondary_unit' => $product->secondary_unit,
            'secondary_price' => $product->secondary_price ? (string) $product->secondary_price : null,
            'image' => $product->coverUrl(),
            'in_stock' => ! $product->track_stock || (float) $product->stock_quantity > 0,
        ];

        if ($detail) {
            $payload['description'] = $product->getTranslations('description');
            $payload['gallery'] = $product->images->map(fn ($image) => $image->url())->filter()->values();
            $payload['services'] = $product->services->map(fn ($service) => [
                'id' => $service->id,
                'name' => $service->getTranslations('name'),
                'type' => $service->surcharge_type,
                'value' => (string) $service->surcharge_value,
            ]);
            $payload['packaging'] = $product->packagingTypes->map(fn ($packaging) => [
                'id' => $packaging->id,
                'name' => $packaging->getTranslations('name'),
                'type' => $packaging->surcharge_type,
                'value' => (string) $packaging->surcharge_value,
            ]);
        }

        return $payload;
    }
}
