<?php

namespace App\Support;

use App\DTO\CartQuote;
use App\Exceptions\CartException;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\DecimalMath;
use App\Services\PricingService;

class SessionCart
{
    public const LINES_KEY = 'cart.lines';

    public const COUPON_KEY = 'cart.coupon';

    public function __construct(
        private DecimalMath $math,
        private PricingService $pricing,
    ) {}

    /**
     * @return array<string, array<string, mixed>>
     */
    public function rawLines(): array
    {
        return session(self::LINES_KEY, []);
    }

    public function couponCode(): ?string
    {
        $code = session(self::COUPON_KEY);

        return filled($code) ? (string) $code : null;
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function add(array $input): CartQuote
    {
        $kind = isset($input['bundle_id']) && empty($input['product_id']) ? 'bundle' : 'product';
        $quantity = $this->math->normalize($input['quantity'] ?? '1');

        if ($this->math->comp($quantity, '0') <= 0 || $this->math->comp($quantity, '10000') > 0) {
            throw new CartException('store.cart.quantity');
        }

        if ($kind === 'product') {
            $product = Product::query()->find($input['product_id'] ?? null);

            if (! $product || ! $product->is_active) {
                throw new CartException('store.cart.unavailable');
            }

            $unit = (string) ($input['unit'] ?? $product->unit);

            if ($product->priceForUnit($unit) === null) {
                throw new CartException('store.cart.unit');
            }

            $line = [
                'kind' => 'product',
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit' => $unit,
                'service_ids' => array_values(array_unique(array_map('intval', $input['service_ids'] ?? []))),
                'packaging_id' => ! empty($input['packaging_id']) ? (int) $input['packaging_id'] : null,
            ];
        } else {
            $bundle = Bundle::query()->find($input['bundle_id'] ?? null);

            if (! $bundle || ! $bundle->is_active) {
                throw new CartException('store.cart.unavailable');
            }

            $line = [
                'kind' => 'bundle',
                'bundle_id' => $bundle->id,
                'quantity' => $quantity,
            ];
        }

        $line['key'] = $this->keyFor($line);
        $lines = $this->rawLines();

        if (isset($lines[$line['key']])) {
            $line['quantity'] = $this->math->add($lines[$line['key']]['quantity'], $quantity);
        }

        $resolved = $this->pricing->resolveLine($line);

        if ($resolved === null) {
            throw new CartException('store.cart.stock');
        }

        $lines[$line['key']] = $line;
        session([self::LINES_KEY => $lines]);

        return $this->quote();
    }

    public function update(string $key, string|int $quantity): CartQuote
    {
        $lines = $this->rawLines();

        if (! isset($lines[$key])) {
            throw new CartException('store.cart.missing');
        }

        $normalized = $this->math->normalize($quantity);

        if ($this->math->comp($normalized, '0') <= 0) {
            unset($lines[$key]);
        } else {
            $lines[$key]['quantity'] = $normalized;
            $resolved = $this->pricing->resolveLine($lines[$key]);

            if ($resolved === null) {
                throw new CartException('store.cart.stock');
            }
        }

        session([self::LINES_KEY => $lines]);

        return $this->quote();
    }

    public function remove(string $key): CartQuote
    {
        $lines = $this->rawLines();
        unset($lines[$key]);
        session([self::LINES_KEY => $lines]);

        return $this->quote();
    }

    public function applyCoupon(string $code): CartQuote
    {
        $quote = $this->pricing->quote(array_values($this->rawLines()), $code);

        if ($quote->couponCode === null) {
            throw new CartException('store.cart.coupon');
        }

        session([self::COUPON_KEY => $quote->couponCode]);

        return $this->quote();
    }

    public function clearCoupon(): void
    {
        session()->forget(self::COUPON_KEY);
    }

    public function clear(): void
    {
        session()->forget([self::LINES_KEY, self::COUPON_KEY]);
    }

    public function quote(?int $cityId = null, ?int $orderPackagingId = null): CartQuote
    {
        $quote = $this->pricing->quote(
            array_values($this->rawLines()),
            $this->couponCode(),
            $cityId,
            $orderPackagingId,
        );

        if ($quote->removed !== []) {
            $lines = $this->rawLines();

            foreach ($quote->lines as $line) {
                $lines[$line->key]['key'] = $line->key;
            }

            $validKeys = array_map(fn ($line) => $line->key, $quote->lines);
            $lines = array_intersect_key($lines, array_flip($validKeys));
            session([self::LINES_KEY => $lines]);
        }

        return $quote;
    }

    /**
     * @param  array<string, mixed>  $line
     */
    public function keyFor(array $line): string
    {
        $services = array_map('intval', $line['service_ids'] ?? []);
        sort($services);

        $payload = [
            $line['kind'] ?? 'product',
            $line['product_id'] ?? null,
            $line['bundle_id'] ?? null,
            $line['unit'] ?? '',
            $services,
            $line['packaging_id'] ?? null,
        ];

        return substr(hash('sha256', (string) json_encode($payload)), 0, 16);
    }
}
