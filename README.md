# M-Pesa Daraja SDK

Laravel SDK for Safaricom's M-Pesa Daraja API.

## Status

C2B Register URLs implemented and tested.

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

MPESA_C2B_SHORTCODE=600984
MPESA_C2B_RESPONSE_TYPE=Completed
MPESA_C2B_CONFIRMATION_URL=https://yourdomain.com/api/c2b/confirmation
MPESA_C2B_VALIDATION_URL=https://yourdomain.com/api/c2b/validation
```

For production, set `MPESA_ENVIRONMENT=production` and `MPESA_BASE_URL=https://api.safaricom.co.ke`.

## C2B Register URLs

One-time setup. Registers your callback URLs so M-Pesa sends payment notifications to your server.

```php
Mpesa::registerC2BUrls();
```

Call this in a deploy script or artisan command — not per-request. URLs are global per shortcode and hitting the endpoint creates a permanent replacement (production supports a one-time, no-overwrite policy; contact Safaricom to change). Production URLs must be HTTPS.

## Testing

```bash
composer test
```

## Analysis

```bash
composer analyse
```
