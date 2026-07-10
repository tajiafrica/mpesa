<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Enums;

/**
 * API endpoint paths for the M-Pesa Daraja API.
 *
 * Each case maps to a specific endpoint. Add new cases
 * as new services are implemented.
 */
enum Paths: string
{
    case OAuthToken = '/oauth/v1/generate?grant_type=client_credentials';
}
