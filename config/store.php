<?php

return [

    'currency' => env('STORE_CURRENCY', 'EGP'),

    'money_scale' => (int) env('STORE_MONEY_SCALE', 4),

    'invoice_ttl_days' => (int) env('STORE_INVOICE_TTL_DAYS', 14),

    'analytics_timezone' => env('ANALYTICS_TIMEZONE', 'Africa/Cairo'),

    'geoip_url' => env('ANALYTICS_GEOIP_URL', 'http://ip-api.com/json/{ip}?fields=status,message,country,countryCode,regionName,city,lat,lon'),

    'ga4_measurement_id' => env('GA4_MEASUREMENT_ID'),

    'meta_pixel_id' => env('META_PIXEL_ID'),

    'sms_driver' => env('SMS_DRIVER', 'log'),

    'sms_http_url' => env('SMS_HTTP_URL'),

    'brand_name' => env('APP_NAME', 'Atlas Market'),

    'theme_defaults' => [
        'brand_name' => env('APP_NAME', 'Atlas Market'),
        'primary' => '#1A2B28',
        'primary_dark' => '#0F1B19',
        'primary_light' => '#2F4F47',
        'accent' => '#C9A55C',
        'accent_light' => '#E0C58A',
        'canvas' => '#DDD6CA',
        'surface' => '#F5EFE6',
        'border' => '#C9A55C',
        'success' => '#2F4F47',
        'danger' => '#8E3B32',
        'theme_color' => '#1A2B28',
    ],

];
