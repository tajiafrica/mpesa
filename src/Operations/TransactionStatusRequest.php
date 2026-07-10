<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

/** Validated Transaction Status query data transfer object. */
class TransactionStatusRequest
{
    /** All fields nullable — cross-field OR is enforced in the builder. */
    public function __construct(
        public readonly ?string $transactionId = null,
        public readonly ?string $originalConversationId = null,
        public readonly ?string $remarks = null,
        public readonly ?string $occasion = null,
    ) {}

    /**
     * Create from raw data, passing through nulls for lazy validation.
     *
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            transactionId: $data['transactionId'] ?? null,
            originalConversationId: $data['originalConversationId'] ?? null,
            remarks: $data['remarks'] ?? null,
            occasion: $data['occasion'] ?? null,
        );
    }
}
