<?php

namespace App\DTO;

class CreateOrderPayload
{
    /**
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function __construct(
        public ?int $userId,
        public string $customerName,
        public ?string $customerEmail,
        public string $customerPhone,
        public int $cityId,
        public string $address,
        public string $paymentGateway,
        public array $lines,
        public ?string $couponCode = null,
        public ?int $orderPackagingId = null,
        public ?string $notes = null,
        public string $locale = 'en',
        public bool $subscriptionRenewal = false,
    ) {}
}
