<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Support;

trait HasHelpers
{
    public function timestamp(): string
    {
        return date('YmdHis');
    }

    public function password(string $timestamp): string
    {
        return base64_encode($this->config->shortcode.$this->config->stkPasskey.$timestamp);
    }
}
