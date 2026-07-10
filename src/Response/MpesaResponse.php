<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Response;

use Psr\Http\Message\ResponseInterface;

/** Universal response envelope for all M-Pesa API calls. */
class MpesaResponse
{
    public function __construct(
        private readonly array $data,
    ) {}

    /** Create from a PSR-7 HTTP response, decoding JSON once. */
    public static function fromResponse(ResponseInterface $response): self
    {
        return new self(json_decode((string) $response->getBody(), true) ?? []);
    }

    /** Read a single field from the response data. */
    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /** Whether the API returned ResponseCode === "0". */
    public function successful(): bool
    {
        return ($this->data['ResponseCode'] ?? null) === '0';
    }

    /** Return the full decoded response as an array. */
    public function raw(): array
    {
        return $this->data;
    }
}
