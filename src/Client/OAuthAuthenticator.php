<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Client;

use GuzzleHttp\Client as GuzzleClient;
use TajiAfrica\Mpesa\Client\Contracts\Authenticator;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;

/**
 * OAuth 2.0 client-credentials authenticator for the Daraja API.
 *
 * Fetches a token on first call and caches it in memory for 3600 seconds.
 * Supports optional user-provided tokens via ::setToken().
 */
class OAuthAuthenticator implements Authenticator
{
    private ?string $cachedToken = null;

    private ?int $expiresAt = null;

    private ?string $userToken = null;

    public function __construct(
        private readonly MpesaConfig $config,
        private readonly GuzzleClient $http,
    ) {}

    public function setToken(?string $token): void
    {
        $this->userToken = $token;
    }

    public function hasUserToken(): bool
    {
        return $this->userToken !== null;
    }

    public function clearUserToken(): void
    {
        $this->userToken = null;
    }

    /**
     * Get a valid OAuth token.
     *
     * Priority:
     * 1. User-provided token (via ::setToken())
     * 2. In-memory cached token (if still within 3600s expiry)
     * 3. Fresh token fetched from the OAuth endpoint
     */
    public function getToken(): string
    {
        if ($this->userToken !== null) {
            return $this->userToken;
        }

        if ($this->cachedToken === null || $this->expiresAt <= time()) {
            $response = $this->http->get(
                $this->config->url(Paths::OAuthToken->value),
                ['auth' => [$this->config->consumerKey, $this->config->consumerSecret]],
            );

            $body = json_decode((string) $response->getBody(), true);
            $this->cachedToken = $body['access_token'];
            $this->expiresAt = time() + 3600;
        }

        return $this->cachedToken;
    }
}
