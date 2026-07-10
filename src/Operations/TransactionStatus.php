<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Exceptions\MpesaValidationException;
use TajiAfrica\Mpesa\Response\MpesaResponse;

/** Fluent builder for querying the status of an M-Pesa transaction. */
class TransactionStatus
{
    private ?string $transactionId = null;

    private ?string $originalConversationId = null;

    private ?string $remarks = null;

    private ?string $occasion = null;

    public function __construct(
        private readonly MpesaClient $client,
        private readonly MpesaConfig $config,
    ) {}

    /** Query by the transaction ID returned during the original request. */
    public function transactionId(string $id): static
    {
        $this->transactionId = $id;

        return $this;
    }

    /** Query by the original conversation ID as a fallback. */
    public function originalConversationId(string $id): static
    {
        $this->originalConversationId = $id;

        return $this;
    }

    /** Optional remarks for the status query. */
    public function remarks(string $remarks): static
    {
        $this->remarks = $remarks;

        return $this;
    }

    /** Optional occasion field for the status query. */
    public function occasion(string $occasion): static
    {
        $this->occasion = $occasion;

        return $this;
    }

    /** Validate (transactionId or originalConversationId) and send. */
    public function send(): MpesaResponse
    {
        $request = TransactionStatusRequest::from([
            'transactionId' => $this->transactionId,
            'originalConversationId' => $this->originalConversationId,
            'remarks' => $this->remarks,
            'occasion' => $this->occasion,
        ]);

        if ($request->transactionId === null && $request->originalConversationId === null) {
            throw new MpesaValidationException('TransactionId or OriginalConversationId is required.');
        }

        return $this->client->post(Paths::TransactionStatus, [
            'Initiator' => $this->config->initiatorName,
            'SecurityCredential' => $this->config->securityCredential,
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => $request->transactionId ?? '',
            'OriginalConversationID' => $request->originalConversationId ?? '',
            'PartyA' => $this->config->shortcode,
            'IdentifierType' => '4',
            'ResultURL' => $this->config->statusResultUrl,
            'QueueTimeOutURL' => $this->config->statusTimeoutUrl,
            'Remarks' => $request->remarks ?? '',
            'Occasion' => $request->occasion ?? '',
        ]);
    }
}
