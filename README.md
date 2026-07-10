# M-Pesa Daraja SDK

Laravel SDK for integrating Safaricom's M-Pesa Daraja API.

## Status

Authentication pipeline is implemented and tested. API operation builders (STK Push, B2C, C2B, etc.) are coming next.

## Install

```bash
composer require tajiafrica/mpesa
php artisan vendor:publish --tag=mpesa-config
```

## Configuration

Add these to your `.env`:

```env
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_ENVIRONMENT=sandbox
MPESA_BASE_URL=https://sandbox.safaricom.co.ke
```

For production, set `MPESA_ENVIRONMENT=production` and `MPESA_BASE_URL=https://api.safaricom.co.ke`.

## Usage

The SDK automatically fetches an OAuth token on the first API call per request lifecycle.

```php
use TajiAfrica\Mpesa\Facades\Mpesa;

// Provide a cached token to avoid fetching on every request
Mpesa::setToken(Cache::get('mpesa_token'));

// Or clear the override to auto-fetch
Mpesa::setToken(null);
```

## Testing

```bash
composer test
```

## Analysis

```bash
composer analyse
```
