<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Response\MpesaResponse;

class Mpesa
{
    public function __construct(
        private readonly MpesaClient $client,
        private readonly MpesaConfig $config,
    ) {}

    public function setToken(?string $token): static
    {
        $this->client->setToken($token);

        return $this;
    }

    public function registerC2BUrls(): MpesaResponse
    {
        return $this->client->post(Paths::C2BRegisterUrls, [
            'ShortCode' => $this->config->c2bShortcode,
            'ResponseType' => $this->config->c2bResponseType,
            'ConfirmationURL' => $this->config->c2bConfirmationUrl,
            'ValidationURL' => $this->config->c2bValidationUrl,
        ]);
    }
}
