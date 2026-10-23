<?php

namespace App\DTO;

class PaymentInitiation
{
    public function __construct(
        public string $paymentStatus,
        public ?string $redirectUrl = null,
        public bool $failed = false,
        public ?string $message = null,
    ) {}
}
