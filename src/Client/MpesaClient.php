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

/** Default Daraja API client — Guzzle-based with Bearer auth and 401 retry. */
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

    /** Send request, catching connection errors as MpesaNetworkException. */
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
