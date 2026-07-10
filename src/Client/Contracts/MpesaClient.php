<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Client\Contracts;

use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Response\MpesaResponse;

/**
 * HTTP client for the M-Pesa Daraja API.
 *
 * Handles authentication, request sending, response wrapping,
 * and transparent 401 retry when a stale user token is detected.
 */
interface MpesaClient
{
    /**
     * Provide a custom OAuth token to override auto-fetching.
     */
    public function setToken(?string $token): void;

    /**
     * Send an authenticated POST request.
     *
     * @param  Paths  $path  API endpoint (from the Paths enum)
     * @param  array<string, mixed>  $data  Request payload
     */
    public function post(Paths $path, array $data): MpesaResponse;
}
