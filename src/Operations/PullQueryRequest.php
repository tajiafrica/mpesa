<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Exceptions\MpesaValidationException;

/** Validated Pull Transactions query data transfer object. */
class PullQueryRequest
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly int $offset,
    ) {}

    /**
     * Create from raw data, throwing on missing required fields.
     *
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            startDate: $data['startDate'] ?? throw new MpesaValidationException('Start date is required.'),
            endDate: $data['endDate'] ?? throw new MpesaValidationException('End date is required.'),
            offset: (int) ($data['offset'] ?? throw new MpesaValidationException('Offset is required.')),
        );
    }
}
