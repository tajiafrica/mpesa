<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\ResponseInterface;
use TajiAfrica\Mpesa\Client\Contracts\Authenticator;
use TajiAfrica\Mpesa\Client\Contracts\MpesaClient as MpesaClientContract;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Exceptions\MpesaNetworkException;
use TajiAfrica\Mpesa\Response\MpesaResponse;

/**
 * Default M-Pesa API client powered by Guzzle.
 *
 * Attaches the Bearer token from the Authenticator, wraps responses
 * in MpesaResponse, and transparently retries once on 401 when a
 * user-provided token has expired.
 */
class MpesaClient implements MpesaClientContract
{
    public function __construct(
        private readonly MpesaConfig $config,
        private readonly Authenticator $authenticator,
        private readonly GuzzleClient $http,
    ) {}

    /** {@inheritdoc} */
    public function setToken(?string $token): void
    {
        $this->authenticator->setToken($token);
    }

    /** {@inheritdoc} */
    public function post(Paths $path, array $data): MpesaResponse
    {
        $response = $this->send($path, $data);

        if ($response->getStatusCode() === 401 && $this->authenticator->hasUserToken()) {
            $this->authenticator->clearUserToken();
            $response = $this->send($path, $data);
        }

        return MpesaResponse::fromResponse($response);
    }

    /**
     * Execute the HTTP request and return the raw PSR-7 response.
     *
     * @throws MpesaNetworkException On connection failure (timeout, DNS, TLS).
     */
    private function send(Paths $path, array $data): ResponseInterface
    {
        try {
            return $this->http->post(
                $this->config->url($path->value),
                [
                    'headers' => ['Authorization' => 'Bearer '.$this->authenticator->getToken()],
                    'json' => $data,
                ],
            );
        } catch (ConnectException $e) {
            throw new MpesaNetworkException('Connection failed: '.$e->getMessage(), 0, $e);
        }
    }
}
