<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Client\Contracts;

/**
 * Authentication strategy for the M-Pesa API.
 *
 * Implementations handle token lifecycle — fetch, cache, refresh —
 * as well as user-provided token overrides.
 */
interface Authenticator
{
    /**
     * Return a valid OAuth access token.
     *
     * Fetches from the API on first call or after expiry.
     * Returns the user-provided token if one was set via ::setToken().
     */
    public function getToken(): string;

    /**
     * Provide a custom token to use instead of auto-fetching.
     *
     * Pass null to clear the override and fall back to auto-fetch.
     */
    public function setToken(?string $token): void;

    /**
     * Whether a user-provided token is currently set.
     */
    public function hasUserToken(): bool;

    /**
     * Clear the user-provided token override.
     *
     * Subsequent calls to ::getToken() will auto-fetch a fresh token.
     */
    public function clearUserToken(): void;
}
