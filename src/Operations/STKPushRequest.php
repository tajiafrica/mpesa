<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Exceptions\MpesaValidationException;

/** Validated STK Push request data transfer object. */
class STKPushRequest
{
    public function __construct(
        public readonly int $amount,
        public readonly string $phone,
        public readonly string $reference,
        public readonly ?string $description = null,
    ) {}

    /** Create from raw data, throwing on missing required fields. */
    public static function from(array $data): self
    {
        return new self(
            amount: $data['amount'] ?? throw new MpesaValidationException('Amount is required.'),
            phone: $data['phone'] ?? throw new MpesaValidationException('Phone number is required.'),
            reference: $data['reference'] ?? throw new MpesaValidationException('Account reference is required.'),
            description: $data['description'] ?? null,
        );
    }
}
