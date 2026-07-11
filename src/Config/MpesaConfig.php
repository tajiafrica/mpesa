<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Config;

/** Immutable SDK configuration built from the mpesa.php config array. */
class MpesaConfig
{
    public function __construct(
        public readonly string $consumerKey,
        public readonly string $consumerSecret,
        public readonly string $environment,
        public readonly string $baseUrl,
        public readonly string $shortcode,
        public readonly string $passkey,
        public readonly string $initiatorName,
        public readonly string $securityCredential,
        public readonly string $c2bResponseType,
        public readonly ?string $c2bConfirmationUrl,
        public readonly ?string $c2bValidationUrl,
        public readonly string $stkTransactionType,
        public readonly ?string $stkPartyB,
        public readonly ?string $stkCallbackUrl,
        public readonly ?string $statusResultUrl,
        public readonly ?string $statusTimeoutUrl,
        public readonly ?string $reversalResultUrl,
        public readonly ?string $reversalTimeoutUrl,
        public readonly ?string $pullNominatedNumber,
        public readonly ?string $pullCallbackUrl,
    ) {}

    /**
     * Create a config instance from the published config array.
     *
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        $c2b = $config['c2b'] ?? [];
        $stk = $config['stk'] ?? [];
        $status = $config['status'] ?? [];
        $reversal = $config['reversal'] ?? [];
        $pull = $config['pull'] ?? [];

        return new self(
            consumerKey: $config['consumer_key'] ?? '',
            consumerSecret: $config['consumer_secret'] ?? '',
            environment: $config['environment'] ?? 'sandbox',
            baseUrl: $config['base_url'] ?? 'https://sandbox.safaricom.co.ke',
            shortcode: $config['shortcode'] ?? '',
            passkey: $config['passkey'] ?? '',
            initiatorName: $config['initiator_name'] ?? '',
            securityCredential: $config['security_credential'] ?? '',
            c2bResponseType: $c2b['response_type'] ?? 'Completed',
            c2bConfirmationUrl: $c2b['confirmation_url'] ?? null,
            c2bValidationUrl: $c2b['validation_url'] ?? null,
            stkTransactionType: $stk['transaction_type'] ?? 'CustomerPayBillOnline',
            stkPartyB: $stk['party_b'] ?? null,
            stkCallbackUrl: $stk['callback_url'] ?? null,
            statusResultUrl: $status['result_url'] ?? null,
            statusTimeoutUrl: $status['timeout_url'] ?? null,
            reversalResultUrl: $reversal['result_url'] ?? null,
            reversalTimeoutUrl: $reversal['timeout_url'] ?? null,
            pullNominatedNumber: $pull['nominated_number'] ?? null,
            pullCallbackUrl: $pull['callback_url'] ?? null,
        );
    }

    /** Prepend the base URL to an API path. */
    public function url(string $path): string
    {
        return $this->baseUrl.$path;
    }
}
