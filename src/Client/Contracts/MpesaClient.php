<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Client\Contracts;

use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Response\MpesaResponse;

/** HTTP client contract for authenticated Daraja API requests. */
interface MpesaClient
{
    /** Override the OAuth token (null to clear). */
    public function setToken(?string $token): void;

    /**
     * Send an authenticated POST and return a wrapped response.
     *
     * @param  array<string, mixed>  $data
     */
    public function post(Paths $path, array $data): MpesaResponse;
}
