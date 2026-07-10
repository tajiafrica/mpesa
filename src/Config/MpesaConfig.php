<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Config;

class MpesaConfig
{
    public function __construct(
        public readonly string $consumerKey,
        public readonly string $consumerSecret,
        public readonly string $environment,
        public readonly string $baseUrl,
        public readonly string $c2bShortcode,
        public readonly string $c2bResponseType,
        public readonly ?string $c2bConfirmationUrl,
        public readonly ?string $c2bValidationUrl,
    ) {}

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        $c2b = $config['c2b'] ?? [];

        return new self(
            consumerKey: $config['consumer_key'] ?? '',
            consumerSecret: $config['consumer_secret'] ?? '',
            environment: $config['environment'] ?? 'sandbox',
            baseUrl: $config['base_url'] ?? 'https://sandbox.safaricom.co.ke',
            c2bShortcode: $c2b['shortcode'] ?? '',
            c2bResponseType: $c2b['response_type'] ?? 'Completed',
            c2bConfirmationUrl: $c2b['confirmation_url'] ?? null,
            c2bValidationUrl: $c2b['validation_url'] ?? null,
        );
    }

    public function url(string $path): string
    {
        return $this->baseUrl.$path;
    }
}
