<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;

/**
 * Entry point for the M-Pesa SDK.
 *
 * Instantiated by the service provider and accessed via the
 * `Mpesa` facade. Each public method returns a fluent builder
 * for the corresponding M-Pesa operation.
 *
 * @method static \TajiAfrica\Mpesa\Client\Contracts\MpesaClient setToken(?string $token)
 */
class Mpesa
{
    public function __construct(
        private readonly MpesaClient $client,
    ) {}

    /**
     * Provide a custom OAuth token to use instead of auto-fetching.
     *
     * Pass null to clear the override and fall back to auto-fetch.
     */
    public function setToken(?string $token): static
    {
        $this->client->setToken($token);

        return $this;
    }
}
