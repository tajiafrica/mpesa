<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Exceptions\MpesaValidationException;

/** Validated Reversal request data transfer object. */
class ReversalRequest
{
    public function __construct(
        public readonly string $transactionId,
        public readonly int $amount,
        public readonly string $remarks,
    ) {}

    /**
     * Create from raw data, throwing on missing required fields.
     *
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            transactionId: $data['transactionId'] ?? throw new MpesaValidationException('Transaction ID is required.'),
            amount: $data['amount'] ?? throw new MpesaValidationException('Amount is required.'),
            remarks: $data['remarks'] ?? throw new MpesaValidationException('Remarks is required.'),
        );
    }
}
