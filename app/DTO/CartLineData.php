<?php

namespace App\DTO;

class CartLineData
{
    /**
     * @param  array<int, array<string, mixed>>  $services
     * @param  array<string, mixed>|null  $packaging
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public string $key,
        public string $kind,
        public string $quantity,
        public string $unitPrice,
        public string $lineBase,
        public string $servicesFee,
        public string $packagingFee,
        public string $lineSubtotal,
        public string $name,
        public array $nameTranslations,
        public ?int $productId,
        public ?int $bundleId,
        public string $unit,
        public array $services,
        public ?array $packaging,
        public array $raw,
        public ?string $imageUrl = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'kind' => $this->kind,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'line_base' => $this->lineBase,
            'services_fee' => $this->servicesFee,
            'packaging_fee' => $this->packagingFee,
            'line_subtotal' => $this->lineSubtotal,
            'name' => $this->name,
            'product_id' => $this->productId,
            'bundle_id' => $this->bundleId,
            'unit' => $this->unit,
            'image_url' => $this->imageUrl,
        ];
    }
}
