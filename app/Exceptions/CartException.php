<?php

namespace App\Exceptions;

use RuntimeException;

class CartException extends RuntimeException
{
    public function __construct(public readonly string $errorKey = 'store.cart.invalid')
    {
        parent::__construct($errorKey);
    }
}
