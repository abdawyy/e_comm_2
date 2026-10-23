<?php

namespace App\Actions;

use App\DTO\CartLineData;
use App\DTO\CreateOrderPayload;
use App\Exceptions\CartException;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\PackagingType;
use App\Models\Product;
use App\Services\DecimalMath;
use App\Services\PricingService;
use App\Support\Phone;
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    public function __construct(
        private PricingService $pricing,
        private DecimalMath $math,
    ) {}

    public function execute(CreateOrderPayload $payload): Order
    {
        return DB::transaction(function () use ($payload) {
            $city = City::query()->where('is_active', true)->lockForUpdate()->find($payload->cityId);

            if (! $city) {
                throw new CartException('store.checkout.city_invalid');
            }

            $quote = $this->pricing->quote(
                $payload->lines,
                $payload->couponCode,
                $city->id,
                $payload->orderPackagingId,
            );

            if ($quote->lines === []) {
                throw new CartException('store.cart.empty');
            }

            if ($payload->couponCode && $quote->couponCode === null) {
                throw new CartException('store.cart.coupon');
            }

            $packaging = null;

            if ($payload->orderPackagingId) {
                $packaging = PackagingType::query()
                    ->where('scope', 'order')
                    ->where('is_active', true)
                    ->find($payload->orderPackagingId);

                if (! $packaging) {
                    throw new CartException('store.checkout.packaging');
                }
            }

            $this->decrementStock($quote->lines);

            $coupon = null;

            if ($quote->couponId) {
                $coupon = Coupon::query()->lockForUpdate()->find($quote->couponId);

                if (! $coupon || ! $coupon->isCurrentlyValid()) {
                    throw new CartException('store.cart.coupon');
                }

                $coupon->increment('used_count');
            }

            $gateway = $payload->paymentGateway;
            $paymentStatus = $gateway === 'paymob_card' ? 'pending' : 'unpaid';

            $order = new Order;
            $order->forceFill([
                'user_id' => $payload->userId,
                'reference' => Order::nextReference(),
                'status' => 'pending',
                'payment_status' => $paymentStatus,
                'payment_gateway' => $gateway,
                'currency' => config('store.currency', 'EGP'),
                'locale' => $payload->locale,
                'customer_name' => $payload->customerName,
                'customer_email' => $payload->customerEmail,
                'customer_phone' => Phone::normalize($payload->customerPhone),
                'city_id' => $city->id,
                'city_name' => (string) $city->name,
                'address' => $payload->address,
                'subtotal' => $quote->subtotal,
                'discount' => $quote->discount,
                'coupon_code' => $quote->couponCode,
                'coupon_id' => $coupon?->id,
                'order_packaging_fee' => $quote->orderPackagingFee,
                'order_packaging_snapshot' => $packaging ? [
                    'id' => $packaging->id,
                    'name' => $packaging->getTranslations('name'),
                    'type' => $packaging->surcharge_type,
                    'value' => $this->math->normalize((string) $packaging->surcharge_value),
                ] : null,
                'shipping_fee' => $quote->shippingFee,
                'total' => $quote->total,
                'amount_cents' => $this->math->toCents($quote->total),
                'notes' => $payload->notes,
            ])->save();

            foreach ($quote->lines as $line) {
                $order->items()->create([
                    'kind' => $line->kind,
                    'product_id' => $line->productId,
                    'bundle_id' => $line->bundleId,
                    'name' => $line->nameTranslations,
                    'unit' => $line->unit,
                    'unit_price' => $line->unitPrice,
                    'quantity' => $line->quantity,
                    'services_snapshot' => $line->services,
                    'packaging_snapshot' => $line->packaging,
                    'line_base' => $line->lineBase,
                    'services_fee' => $line->servicesFee,
                    'packaging_fee' => $line->packagingFee,
                    'line_subtotal' => $line->lineSubtotal,
                ]);
            }

            if ($payload->subscriptionRenewal) {
                $order->orderNotes()->create([
                    'body' => 'Subscription renewal. Cash on delivery — saved cards are not charged.',
                    'type' => 'subscription',
                ]);
            }

            return $order->load('items');
        });
    }

    /**
     * @param  array<int, CartLineData>  $lines
     */
    private function decrementStock(array $lines): void
    {
        $needed = [];

        foreach ($lines as $line) {
            if ($line->kind === 'product' && $line->productId) {
                $needed[$line->productId] = $this->math->add($needed[$line->productId] ?? '0', $line->quantity);
            }

            if ($line->kind === 'bundle' && $line->bundleId) {
                $bundle = \App\Models\Bundle::query()->with('items')->find($line->bundleId);

                foreach ($bundle?->items ?? [] as $item) {
                    $qty = $this->math->mul((string) $item->quantity, $line->quantity);
                    $needed[$item->product_id] = $this->math->add($needed[$item->product_id] ?? '0', $qty);
                }
            }
        }

        if ($needed === []) {
            return;
        }

        $products = Product::query()->whereIn('id', array_keys($needed))->lockForUpdate()->get()->keyBy('id');

        foreach ($needed as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product || ! $product->is_active) {
                throw new CartException('store.cart.unavailable');
            }

            if (! $product->track_stock) {
                continue;
            }

            if ($this->math->comp((string) $product->stock_quantity, $quantity) < 0) {
                throw new CartException('store.cart.stock');
            }

            $product->stock_quantity = $this->math->sub((string) $product->stock_quantity, $quantity);
            $product->save();
        }
    }
}
