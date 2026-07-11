# M-Pesa Daraja SDK

Laravel SDK for Safaricom's M-Pesa Daraja API.

## Status

C2B Register URLs, STK Push, Transaction Status, Reversal, and Pull Transactions implemented and tested.

## Installation

Add the repository to your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/your-org/mpesa"
    }
]
```

Then require it:

```bash
composer require tajiafrica/mpesa
```

Laravel auto-discovers the service provider and facade. No manual registration needed.

Publish the config file:

```bash
php artisan vendor:publish --tag=mpesa-config
```

This creates `config/mpesa.php`.

## Configuration

Set these environment variables in your `.env`:

```env
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_ENVIRONMENT=sandbox
MPESA_BASE_URL=https://sandbox.safaricom.co.ke
MPESA_SHORTCODE=600984
SAFARICOM_PASSKEY=your_passkey
MPESA_INITIATOR_NAME=testapi
MPESA_SECURITY_CREDENTIAL=your_security_credential

# C2B
MPESA_C2B_RESPONSE_TYPE=Completed
MPESA_C2B_CONFIRMATION_URL=https://yourdomain.com/api/c2b/confirmation
MPESA_C2B_VALIDATION_URL=https://yourdomain.com/api/c2b/validation

# STK Push
MPESA_STK_TRANSACTION_TYPE=CustomerPayBillOnline
MPESA_STK_CALLBACK_URL=https://yourdomain.com/api/stk/callback

# Transaction Status
MPESA_STATUS_RESULT_URL=https://yourdomain.com/api/status/result
MPESA_STATUS_TIMEOUT_URL=https://yourdomain.com/api/status/timeout

# Reversal
MPESA_REVERSAL_RESULT_URL=https://yourdomain.com/api/reversal/result
MPESA_REVERSAL_TIMEOUT_URL=https://yourdomain.com/api/reversal/timeout

# Pull Transactions
MPESA_PULL_NOMINATED_NUMBER=254722000000
MPESA_PULL_CALLBACK_URL=https://yourdomain.com/api/pull/callback
```

For production:

```env
MPESA_ENVIRONMENT=production
MPESA_BASE_URL=https://api.safaricom.co.ke
```

Globals (`initiator_name`, `security_credential`) are shared across Transaction Status and Reversal. Pull Transactions uses its own `nominated_number` and `callback_url` from the `pull` group. Operation-specific callbacks live under their own group in the config.

## Usage

All operations use the `Mpesa` facade:

```php
use TajiAfrica\Mpesa\Facades\Mpesa;
```

### C2B Register URLs

One-time setup that registers callback URLs with Safaricom so M-Pesa sends payment notifications to your server.

```php
Mpesa::registerC2BUrls();
```

- **Sandbox** — register before each test simulation. URLs are overwritable.
- **Production** — register once. To change, delete existing URLs via the [Daraja portal](https://developer.safaricom.co.ke/SelfServices?tab=urlmanagement) (requires two Business Manager/Admin operators on the [M-Pesa Org portal](https://org.ke.m-pesa.com/orglogin.action)), then re-register.
- URLs must be HTTPS in production.

### STK Push (Lipa Na M-Pesa Online)

Sends a payment prompt to the customer's phone. They enter their M-Pesa PIN to authorise.

```php
$response = Mpesa::stkPush()
    ->amount(100)
    ->phone('254708374149')
    ->reference('INV-001')
    ->description('Order payment')
    ->send();

$checkoutRequestId = $response->get('CheckoutRequestID');
```

The result arrives asynchronously at your `MPESA_STK_CALLBACK_URL`. Query the status later:

```php
$status = Mpesa::stkPushQuery($checkoutRequestId);
```

### Transaction Status

Query the status of any transaction by M-Pesa receipt number or original conversation ID. Async — the result is delivered to your result URL.

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

### Reversal

Reverse a completed transaction using the original M-Pesa receipt number.

```php
Mpesa::reversal()
    ->transactionId('PDU91HIVIT')
    ->amount(200)
    ->remarks('Payment reversal')
    ->send();
```

Async — the result arrives at your `MPESA_REVERSAL_RESULT_URL`.

### Pull Transactions

Reconciliation tool that retrieves C2B transactions under your Pay Bill/Till number within the last 48 hours. Register once, then pull on demand.

```php
// One-time registration
Mpesa::registerPull();

// Query transactions within a time range
Mpesa::pullTransactions()
    ->from('2024-01-01 00:00:00')
    ->to('2024-01-02 00:00:00')
    ->offset(0)
    ->send();
```

- Register Pull is a one-time setup. The shortcode must be live and operating in production.
- The query is **synchronous** — returns transaction data directly in the response body.
- `response_code` `1000` means success (Pull uses `1000`, not the `"0"` used by other APIs).

## Response

Every `send()` call returns an `MpesaResponse` instance:

```php
$response->successful();    // bool — true when ResponseCode === "0"
$response->get('key');      // mixed — single field from the response
$response->raw();           // array — full decoded payload
```

## Testing

```bash
composer test
```

## Analysis

```bash
composer analyse
```
