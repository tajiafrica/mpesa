<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Universal response envelope for all M-Pesa API calls.
 *
 * Wraps the decoded JSON body and provides convenient accessors.
 * Consumer inspects the response — the SDK never throws for business codes.
 */
class MpesaResponse
{
    public function __construct(
        private readonly array $data,
    ) {}

    /**
     * Create an instance from a PSR-7 HTTP response.
     *
     * Decodes the JSON body once. Malformed JSON results in an empty array.
     */
    public static function fromResponse(ResponseInterface $response): self
    {
        return new self(json_decode((string) $response->getBody(), true) ?? []);
    }

    /**
     * Retrieve a single field from the response data.
     */
    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Whether the API responded with a success code (ResponseCode === "0").
     */
    public function successful(): bool
    {
        return ($this->data['ResponseCode'] ?? null) === '0';
    }

    /**
     * Return the full decoded response as an array.
     */
    public function raw(): array
    {
        return $this->data;
    }
}
