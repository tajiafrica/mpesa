<?php

declare(strict_types=1);

return [
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'base_url' => env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke'),

    'c2b' => [
        'shortcode' => env('MPESA_C2B_SHORTCODE'),
        'response_type' => env('MPESA_C2B_RESPONSE_TYPE', 'Completed'),
        'confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL'),
        'validation_url' => env('MPESA_C2B_VALIDATION_URL'),
    ],
];
