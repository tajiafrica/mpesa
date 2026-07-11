<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Operations;

use TajiAfrica\Mpesa\Client\Contracts\MpesaClient;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Response\MpesaResponse;

/** Fluent builder for pulling C2B transaction records. */
class PullQuery
{
    private ?string $startDate = null;

    private ?string $endDate = null;

    private int|string|null $offset = null;

    public function __construct(
        private readonly MpesaClient $client,
        private readonly MpesaConfig $config,
    ) {}

    /** Set the start of the query period (YYYY-MM-DD HH:mm:ss). */
    public function from(string $date): static
    {
        $this->startDate = $date;

        return $this;
    }

    /** Set the end of the query period (YYYY-MM-DD HH:mm:ss). */
    public function to(string $date): static
    {
        $this->endDate = $date;

        return $this;
    }

    /** Set the offset for pagination (starts at 0). */
    public function offset(int|string $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    /** Validate and send the pull query request. */
    public function send(): MpesaResponse
    {
        $request = PullQueryRequest::from([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'offset' => $this->offset,
        ]);

        return $this->client->post(Paths::PullQuery, [
            'ShortCode' => $this->config->shortcode,
            'StartDate' => $request->startDate,
            'EndDate' => $request->endDate,
            'OffSetValue' => $request->offset,
        ]);
    }
}
