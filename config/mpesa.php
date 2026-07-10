<?php

declare(strict_types=1);

return [
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'base_url' => env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke'),
    'shortcode' => env('MPESA_SHORTCODE'),

    'c2b' => [
        'response_type' => env('MPESA_C2B_RESPONSE_TYPE', 'Completed'),
        'confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL'),
        'validation_url' => env('MPESA_C2B_VALIDATION_URL'),
    ],

    'stk' => [
        'passkey' => env('MPESA_STK_PASSKEY'),
        'callback_url' => env('MPESA_STK_CALLBACK_URL'),
        'transaction_type' => env('MPESA_STK_TRANSACTION_TYPE', 'CustomerPayBillOnline'),
        'party_b' => env('MPESA_STK_PARTY_B'),
    ],

    'status' => [
        'initiator_name' => env('MPESA_STATUS_INITIATOR_NAME'),
        'security_credential' => env('MPESA_STATUS_SECURITY_CREDENTIAL'),
        'result_url' => env('MPESA_STATUS_RESULT_URL'),
        'timeout_url' => env('MPESA_STATUS_TIMEOUT_URL'),
    ],
];
