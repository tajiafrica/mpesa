<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Client;

use GuzzleHttp\Client as GuzzleClient;
use TajiAfrica\Mpesa\Client\Contracts\Authenticator;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;

/** Fetches and caches OAuth tokens; supports user-supplied token override. */
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

    /** Priority: user token, cached token, fresh fetch. */
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
