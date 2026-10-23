<?php

namespace App\DTO;

class CartQuote
{
    /**
     * @param  array<int, CartLineData>  $lines
     * @param  array<int, string>  $removed
     */
    public function __construct(
        public array $lines,
        public string $subtotal,
        public string $discount,
        public string $orderPackagingFee,
        public string $shippingFee,
        public string $total,
        public ?string $couponCode,
        public ?int $couponId,
        public array $removed = [],
        public ?string $orderPackagingName = null,
    ) {}

    public function count(): string
    {
        $math = app(\App\Services\DecimalMath::class);
        $count = $math->zero();

        foreach ($this->lines as $line) {
            $count = $math->add($count, $line->quantity);
        }

        return $count;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'lines' => array_map(fn (CartLineData $line) => $line->toArray(), $this->lines),
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'order_packaging_fee' => $this->orderPackagingFee,
            'shipping_fee' => $this->shippingFee,
            'total' => $this->total,
            'coupon_code' => $this->couponCode,
            'removed' => $this->removed,
            'count' => $this->count(),
        ];
    }
}
