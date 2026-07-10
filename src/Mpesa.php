<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Operations\STKPush;
use TajiAfrica\Mpesa\Operations\TransactionStatus;
use TajiAfrica\Mpesa\Response\MpesaResponse;
use TajiAfrica\Mpesa\Support\HasHelpers;

class Mpesa
{
    use HasHelpers;

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
            'ShortCode' => $this->config->shortcode,
            'ResponseType' => $this->config->c2bResponseType,
            'ConfirmationURL' => $this->config->c2bConfirmationUrl,
            'ValidationURL' => $this->config->c2bValidationUrl,
        ]);
    }

    public function stkPush(): STKPush
    {
        return new STKPush($this->client, $this->config);
    }

    public function stkPushQuery(string $checkoutRequestId): MpesaResponse
    {
        $timestamp = $this->timestamp();
        $password = $this->password($timestamp);

        return $this->client->post(Paths::STKPushQuery, [
            'BusinessShortCode' => $this->config->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ]);
    }

    public function transactionStatus(): TransactionStatus
    {
        return new TransactionStatus($this->client, $this->config);
    }
}
