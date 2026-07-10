<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Support;

/** Timestamp and password helpers for STK operations. */
trait HasHelpers
{
    /** Generate a formatted timestamp in YYYYMMDDHHmmss. */
    public function timestamp(): string
    {
        return date('YmdHis');
    }

    /** Base64-encoded STK password (shortcode + passkey + timestamp). */
    public function password(string $timestamp): string
    {
        return base64_encode($this->config->shortcode.$this->config->passkey.$timestamp);
    }
}
