# M-Pesa Daraja SDK

Laravel SDK for Safaricom's M-Pesa Daraja API.

## Status

C2B Register URLs, STK Push, Transaction Status, and Reversal implemented and tested.

## Install

```bash
composer require tajiafrica/mpesa
php artisan vendor:publish --tag=mpesa-config
```

## Configuration

```env
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_ENVIRONMENT=sandbox
MPESA_BASE_URL=https://sandbox.safaricom.co.ke
MPESA_SHORTCODE=600984
SAFARICOM_PASSKEY=your_passkey
MPESA_INITIATOR_NAME=your_initiator
MPESA_SECURITY_CREDENTIAL=your_security_credential

# C2B
MPESA_C2B_RESPONSE_TYPE=Completed
MPESA_C2B_CONFIRMATION_URL=https://yourdomain.com/api/c2b/confirmation
MPESA_C2B_VALIDATION_URL=https://yourdomain.com/api/c2b/validation

# STK Push
MPESA_STK_TRANSACTION_TYPE=CustomerPayBillOnline
MPESA_STK_PARTY_B=
MPESA_STK_CALLBACK_URL=https://yourdomain.com/api/stk/callback

# Transaction Status
MPESA_STATUS_RESULT_URL=https://yourdomain.com/api/status/result
MPESA_STATUS_TIMEOUT_URL=https://yourdomain.com/api/status/timeout

# Reversal
MPESA_REVERSAL_RESULT_URL=https://yourdomain.com/api/reversal/result
MPESA_REVERSAL_TIMEOUT_URL=https://yourdomain.com/api/reversal/timeout
```

For production, set `MPESA_ENVIRONMENT=production` and `MPESA_BASE_URL=https://api.safaricom.co.ke`.

`initiator_name` and `security_credential` are shared across Transaction Status and Reversal. Operation-specific callbacks live under their respective groups.

## C2B Register URLs

One-time setup. Registers callback URLs so M-Pesa sends payment notifications to your server.

```php
Mpesa::registerC2BUrls();
```

- **Sandbox** — register before each simulation. Overwritable.
- **Production** — register once. To change, delete existing URLs via [Daraja portal](https://developer.safaricom.co.ke/SelfServices?tab=urlmanagement) (requires two Business Manager/Admin operators on the [M-Pesa Org portal](https://org.ke.m-pesa.com/orglogin.action)), then re-register.
- URLs must be HTTPS in production.

## STK Push (Lipa na M-Pesa)

Sends a payment prompt to a customer's phone. They enter their PIN to complete the transaction.

```php
$response = Mpesa::stkPush()
    ->amount(100)
    ->phone('254708374149')
    ->reference('INV-001')
    ->description('Order payment')
    ->send();

$response->get('CheckoutRequestID'); // wc_CO_...

// Query transaction status
$status = Mpesa::stkPushQuery('ws_CO_...');
```

Result is delivered asynchronously to your `MPESA_STK_CALLBACK_URL`.

## Transaction Status

Queries the status of any transaction by M-Pesa receipt number or original conversation ID. Async — result comes to your result URL.

```php
// By M-Pesa receipt number
Mpesa::transactionStatus()
    ->transactionId('NEF61H8J60')
    ->send();

// By original conversation ID
Mpesa::transactionStatus()
    ->originalConversationId('7071-4170-...')
    ->send();
```

## Reversal

Reverses a completed transaction. Requires an M-Pesa receipt number, the original amount, and remarks.

```php
Mpesa::reversal()
    ->transactionId('PDU91HIVIT')
    ->amount(200)
    ->remarks('Payment reversal')
    ->send();
```

Async — result arrives at your `MPESA_REVERSAL_RESULT_URL`.

## Testing

```bash
composer test
```

## Analysis

```bash
composer analyse
```
