<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Config;

/**
 * Immutable config value object wrapping the mpesa.php config array.
 */
class MpesaConfig
{
    public function __construct(
        public readonly string $consumerKey,
        public readonly string $consumerSecret,
        public readonly string $environment,
        public readonly string $baseUrl,
        public readonly string $shortcode,
        public readonly string $c2bResponseType,
        public readonly ?string $c2bConfirmationUrl,
        public readonly ?string $c2bValidationUrl,
        public readonly string $stkPasskey,
        public readonly ?string $stkCallbackUrl,
        public readonly string $stkTransactionType,
        public readonly ?string $stkPartyB,
    ) {}

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        $c2b = $config['c2b'] ?? [];
        $stk = $config['stk'] ?? [];

        return new self(
            consumerKey: $config['consumer_key'] ?? '',
            consumerSecret: $config['consumer_secret'] ?? '',
            environment: $config['environment'] ?? 'sandbox',
            baseUrl: $config['base_url'] ?? 'https://sandbox.safaricom.co.ke',
            shortcode: $config['shortcode'] ?? '',
            c2bResponseType: $c2b['response_type'] ?? 'Completed',
            c2bConfirmationUrl: $c2b['confirmation_url'] ?? null,
            c2bValidationUrl: $c2b['validation_url'] ?? null,
            stkPasskey: $stk['passkey'] ?? '',
            stkCallbackUrl: $stk['callback_url'] ?? null,
            stkTransactionType: $stk['transaction_type'] ?? 'CustomerPayBillOnline',
            stkPartyB: $stk['party_b'] ?? null,
        );
    }

    /** Build a full request URL from base URL and path. */
    public function url(string $path): string
    {
        return $this->baseUrl.$path;
    }
}
