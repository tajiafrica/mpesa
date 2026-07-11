<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Response\MpesaResponse;

/** Registers the shortcode for Pull Transactions (one-time setup). */
class PullRegistration
{
    public function __construct(
        private readonly MpesaClient $client,
        private readonly MpesaConfig $config,
    ) {}

    public function register(): MpesaResponse
    {
        return $this->client->post(Paths::PullRegister, [
            'ShortCode' => $this->config->shortcode,
            'RequestType' => 'Pull',
            'NominatedNumber' => $this->config->pullNominatedNumber,
            'CallBackURL' => $this->config->pullCallbackUrl,
        ]);
    }
}
