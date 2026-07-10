<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Client\Contracts;

/** Authentication strategy: token lifecycle and user-token overrides. */
interface Authenticator
{
    /** Return a valid OAuth access token. */
    public function getToken(): string;

    /** Provide or clear (null) a user-supplied token override. */
    public function setToken(?string $token): void;

    /** Whether a user-supplied token override is active. */
    public function hasUserToken(): bool;

    /** Drop the user-supplied token; subsequent calls auto-fetch. */
    public function clearUserToken(): void;
}
