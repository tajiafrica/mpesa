<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Response\MpesaResponse;
use TajiAfrica\Mpesa\Support\HasHelpers;

class STKPush
{
    use HasHelpers;

    private ?int $amount = null;

    private ?string $phone = null;

    private ?string $reference = null;

    private ?string $description = null;

    public function __construct(
        private readonly MpesaClient $client,
        private readonly MpesaConfig $config,
    ) {}

    public function amount(int|string $amount): static
    {
        $this->amount = (int) $amount;

        return $this;
    }

    public function phone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function reference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function send(): MpesaResponse
    {
        $request = STKPushRequest::from([
            'amount' => $this->amount,
            'phone' => $this->phone,
            'reference' => $this->reference,
            'description' => $this->description,
        ]);

        $timestamp = $this->timestamp();
        $password = $this->password($timestamp);

        return $this->client->post(Paths::STKPush, [
            'BusinessShortCode' => $this->config->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $this->config->stkTransactionType,
            'Amount' => $request->amount,
            'PartyA' => $request->phone,
            'PartyB' => $this->config->stkPartyB ?? $this->config->shortcode,
            'PhoneNumber' => $request->phone,
            'CallBackURL' => $this->config->stkCallbackUrl,
            'AccountReference' => $request->reference,
            'TransactionDesc' => $request->description ?? '',
        ]);
    }
}
