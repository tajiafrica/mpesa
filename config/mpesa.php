<?php

declare(strict_types=1);

return [
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'base_url' => env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke'),
    'shortcode' => env('MPESA_SHORTCODE'),
    'passkey' => env('SAFARICOM_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),
    'initiator_name' => env('MPESA_INITIATOR_NAME', 'testapi'),
    'security_credential' => env('MPESA_SECURITY_CREDENTIAL'),

    'c2b' => [
        'response_type' => env('MPESA_C2B_RESPONSE_TYPE', 'Completed'),
        'confirmation_url' => env('MPESA_C2B_CONFIRMATION_URL'),
        'validation_url' => env('MPESA_C2B_VALIDATION_URL'),
    ],

    'stk' => [
        'transaction_type' => env('MPESA_STK_TRANSACTION_TYPE', 'CustomerPayBillOnline'),
        'party_b' => env('MPESA_STK_PARTY_B'),
        'callback_url' => env('MPESA_STK_CALLBACK_URL'),
    ],

    'status' => [
        'result_url' => env('MPESA_STATUS_RESULT_URL'),
        'timeout_url' => env('MPESA_STATUS_TIMEOUT_URL'),
    ],

    'reversal' => [
        'result_url' => env('MPESA_REVERSAL_RESULT_URL'),
        'timeout_url' => env('MPESA_REVERSAL_TIMEOUT_URL'),
    ],
];
