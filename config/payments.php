<?php

return [

    'paymob' => [
        'enabled' => filter_var(env('PAYMOB_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'api_key' => env('PAYMOB_API_KEY'),
        'secret_key' => env('PAYMOB_SECRET_KEY'),
        'public_key' => env('PAYMOB_PUBLIC_KEY'),
        'card_integration_id' => env('PAYMOB_CARD_INTEGRATION_ID'),
        'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
        'currency' => env('PAYMOB_CURRENCY', 'EGP'),
        'base_url' => rtrim((string) env('PAYMOB_BASE_URL', 'https://accept.paymob.com'), '/'),
        'notification_url' => env('PAYMOB_NOTIFICATION_URL'),
        'redirection_url' => env('PAYMOB_REDIRECTION_URL'),
    ],

];
