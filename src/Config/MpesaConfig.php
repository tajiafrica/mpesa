<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Config;

/**
 * Immutable configuration value object for the M-Pesa SDK.
 *
 * Constructed via ::fromArray() which expects keys matching the published
 * config/mpesa.php file. Typed properties provide IDE autocompletion
 * and prevent magic-string typos throughout the codebase.
 */
class MpesaConfig
{
    public function __construct(
        public readonly string $consumerKey,
        public readonly string $consumerSecret,
        public readonly string $environment,
        public readonly string $baseUrl,
    ) {}

    /**
     * Create from the array returned by config('mpesa').
     *
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        return new self(
            $config['consumer_key'],
            $config['consumer_secret'],
            $config['environment'],
            $config['base_url'],
        );
    }

    /**
     * Build a full request URL from the base URL and a relative path.
     */
    public function url(string $path): string
    {
        return $this->baseUrl.$path;
    }
}
