<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Response\MpesaResponse;

/** Fluent builder for reversing an M-Pesa transaction. */
class Reversal
{
    private ?string $transactionId = null;

    private int|string|null $amount = null;

    private ?string $remarks = null;

    public function __construct(
        private readonly MpesaClient $client,
        private readonly MpesaConfig $config,
    ) {}

    /** Set the original transaction ID to reverse. */
    public function transactionId(string $id): static
    {
        $this->transactionId = $id;

        return $this;
    }

    /** Set the amount to reverse. */
    public function amount(int|string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /** Set reversal remarks. */
    public function remarks(string $remarks): static
    {
        $this->remarks = $remarks;

        return $this;
    }

    /** Validate and send the reversal request. */
    public function send(): MpesaResponse
    {
        $request = ReversalRequest::from([
            'transactionId' => $this->transactionId,
            'amount' => $this->amount,
            'remarks' => $this->remarks,
        ]);

        return $this->client->post(Paths::Reversal, [
            'Initiator' => $this->config->initiatorName,
            'SecurityCredential' => $this->config->securityCredential,
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $request->transactionId,
            'Amount' => $request->amount,
            'ReceiverParty' => $this->config->shortcode,
            'RecieverIdentifierType' => '11',
            'ResultURL' => $this->config->reversalResultUrl,
            'QueueTimeOutURL' => $this->config->reversalTimeoutUrl,
            'Remarks' => $request->remarks,
        ]);
    }
}
