<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

class TransactionStatusRequest
{
    public function __construct(
        public readonly ?string $transactionId = null,
        public readonly ?string $originalConversationId = null,
        public readonly ?string $remarks = null,
        public readonly ?string $occasion = null,
    ) {}

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
