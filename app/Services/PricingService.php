<?php

namespace App\Services;

use App\DTO\CartLineData;
use App\DTO\CartQuote;
use App\Models\Bundle;
use App\Models\City;
use App\Models\Coupon;
use App\Models\PackagingType;
use App\Models\PreparationService;
use App\Models\Product;
use Illuminate\Support\Collection;

class PricingService
{
    public function __construct(private DecimalMath $math) {}

    /**
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function quote(array $lines, ?string $couponCode = null, ?int $cityId = null, ?int $orderPackagingId = null): CartQuote
    {
        $resolved = [];
        $removed = [];

        foreach ($lines as $line) {
            $priced = $this->resolveLine($line);

            if ($priced === null) {
                $removed[] = (string) ($line['key'] ?? $line['kind'] ?? 'line');

                continue;
            }

            $resolved[] = $priced;
        }

        $subtotal = $this->math->zero();

        foreach ($resolved as $line) {
            $subtotal = $this->math->add($subtotal, $line->lineSubtotal);
        }

        $coupon = $this->validCoupon($couponCode, $subtotal);
        $discount = $coupon ? $this->discountFor($coupon, $subtotal) : $this->math->zero();
        $packagingFee = $this->orderPackagingFee($orderPackagingId, $subtotal);
        $shipping = $this->shippingFee($cityId);
        $total = $this->math->maxZero($this->math->sub($subtotal, $discount));
        $total = $this->math->add($total, $packagingFee);
        $total = $this->math->add($total, $shipping);

        return new CartQuote(
            lines: $resolved,
            subtotal: $subtotal,
            discount: $discount,
            orderPackagingFee: $packagingFee,
            shippingFee: $shipping,
            total: $total,
            couponCode: $coupon?->code,
            couponId: $coupon?->id,
            removed: $removed,
            orderPackagingName: $orderPackagingId ? PackagingType::query()->find($orderPackagingId)?->name : null,
        );
    }

    /**
     * @param  array<string, mixed>  $line
     */
    public function resolveLine(array $line): ?CartLineData
    {
        $kind = $line['kind'] ?? (isset($line['bundle_id']) ? 'bundle' : 'product');

        return $kind === 'bundle'
            ? $this->resolveBundle($line)
            : $this->resolveProduct($line);
    }

    /**
     * @param  array<string, mixed>  $line
     */
    private function resolveProduct(array $line): ?CartLineData
    {
        $product = Product::query()->with(['services', 'packagingTypes', 'images'])->find($line['product_id'] ?? null);

        if (! $product || ! $product->is_active) {
            return null;
        }

        $unit = (string) ($line['unit'] ?? $product->unit);
        $unitPrice = $product->priceForUnit($unit);

        if ($unitPrice === null) {
            return null;
        }

        $quantity = $this->math->normalize($line['quantity'] ?? '1');

        if ($this->math->comp($quantity, '0') <= 0) {
            return null;
        }

        if ($product->track_stock && $this->math->comp((string) $product->stock_quantity, $quantity) < 0) {
            return null;
        }

        $serviceIds = collect($line['service_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        $services = $product->services->whereIn('id', $serviceIds)->where('is_active', true)->values();

        if ($services->count() !== $serviceIds->count()) {
            return null;
        }

        $packaging = null;

        if (! empty($line['packaging_id'])) {
            $packaging = $product->packagingTypes->firstWhere('id', (int) $line['packaging_id']);

            if (! $packaging || ! $packaging->is_active || $packaging->scope !== 'line') {
                return null;
            }
        }

        $lineBase = $this->math->mul($unitPrice, $quantity);
        $servicesFee = $this->math->zero();
        $serviceSnapshots = [];

        foreach ($services as $service) {
            $fee = $this->math->surcharge($lineBase, $service->surcharge_type, (string) $service->surcharge_value);
            $servicesFee = $this->math->add($servicesFee, $fee);
            $serviceSnapshots[] = $this->snapshotAddon($service, $fee);
        }

        $afterServices = $this->math->add($lineBase, $servicesFee);
        $packagingFee = $this->math->zero();
        $packagingSnapshot = null;

        if ($packaging) {
            $packagingFee = $this->math->surcharge($afterServices, $packaging->surcharge_type, (string) $packaging->surcharge_value);
            $packagingSnapshot = $this->snapshotAddon($packaging, $packagingFee);
        }

        $lineSubtotal = $this->math->add($afterServices, $packagingFee);

        return new CartLineData(
            key: (string) ($line['key'] ?? ''),
            kind: 'product',
            quantity: $quantity,
            unitPrice: $unitPrice,
            lineBase: $lineBase,
            servicesFee: $servicesFee,
            packagingFee: $packagingFee,
            lineSubtotal: $lineSubtotal,
            name: (string) $product->name,
            nameTranslations: $product->getTranslations('name'),
            productId: $product->id,
            bundleId: null,
            unit: $unit,
            services: $serviceSnapshots,
            packaging: $packagingSnapshot,
            raw: $line,
            imageUrl: $product->coverUrl(),
        );
    }

    /**
     * @param  array<string, mixed>  $line
     */
    private function resolveBundle(array $line): ?CartLineData
    {
        $bundle = Bundle::query()->with('items.product')->find($line['bundle_id'] ?? null);

        if (! $bundle || ! $bundle->is_active) {
            return null;
        }

        $quantity = $this->math->normalize($line['quantity'] ?? '1');

        if ($this->math->comp($quantity, '0') <= 0) {
            return null;
        }

        foreach ($bundle->items as $item) {
            $product = $item->product;

            if (! $product || ! $product->is_active) {
                return null;
            }

            if ($product->track_stock) {
                $needed = $this->math->mul((string) $item->quantity, $quantity);

                if ($this->math->comp((string) $product->stock_quantity, $needed) < 0) {
                    return null;
                }
            }
        }

        $unitPrice = $this->math->normalize((string) $bundle->price);
        $lineBase = $this->math->mul($unitPrice, $quantity);

        return new CartLineData(
            key: (string) ($line['key'] ?? ''),
            kind: 'bundle',
            quantity: $quantity,
            unitPrice: $unitPrice,
            lineBase: $lineBase,
            servicesFee: $this->math->zero(),
            packagingFee: $this->math->zero(),
            lineSubtotal: $lineBase,
            name: (string) $bundle->name,
            nameTranslations: $bundle->getTranslations('name'),
            productId: null,
            bundleId: $bundle->id,
            unit: 'pack',
            services: [],
            packaging: null,
            raw: $line,
            imageUrl: $bundle->coverUrl(),
        );
    }

    public function validCoupon(?string $code, string $subtotal): ?Coupon
    {
        if (blank($code)) {
            return null;
        }

        $coupon = Coupon::query()->whereRaw('lower(code) = ?', [mb_strtolower(trim($code))])->first();

        if (! $coupon || ! $coupon->isCurrentlyValid()) {
            return null;
        }

        if ($coupon->min_subtotal !== null && $this->math->comp($subtotal, (string) $coupon->min_subtotal) < 0) {
            return null;
        }

        return $coupon;
    }

    public function discountFor(Coupon $coupon, string $subtotal): string
    {
        $discount = $coupon->type === 'percent'
            ? $this->math->percent($subtotal, (string) $coupon->value)
            : $this->math->normalize((string) $coupon->value);

        return $this->math->min($discount, $subtotal);
    }

    public function orderPackagingFee(?int $packagingId, string $subtotal): string
    {
        if (! $packagingId) {
            return $this->math->zero();
        }

        $packaging = PackagingType::query()->where('scope', 'order')->where('is_active', true)->find($packagingId);

        if (! $packaging) {
            return $this->math->zero();
        }

        return $this->math->surcharge($subtotal, $packaging->surcharge_type, (string) $packaging->surcharge_value);
    }

    public function shippingFee(?int $cityId): string
    {
        if (! $cityId) {
            return $this->math->zero();
        }

        $city = City::query()->where('is_active', true)->find($cityId);

        return $city ? $this->math->normalize((string) $city->shipping_fee) : $this->math->zero();
    }

    /**
     * @return array<string, mixed>
     */
    private function snapshotAddon(PreparationService|PackagingType $addon, string $fee): array
    {
        return [
            'id' => $addon->id,
            'name' => $addon->getTranslations('name'),
            'type' => $addon->surcharge_type,
            'value' => $this->math->normalize((string) $addon->surcharge_value),
            'fee' => $fee,
        ];
    }

    /**
     * @param  Collection<int, CartLineData>  $lines
     */
    public function sum(Collection $lines, string $field): string
    {
        $total = $this->math->zero();

        foreach ($lines as $line) {
            $total = $this->math->add($total, (string) $line->{$field});
        }

        return $total;
    }
}
