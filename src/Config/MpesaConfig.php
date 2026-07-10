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
        public readonly string $shortcode,
        public readonly string $c2bResponseType,
        public readonly ?string $c2bConfirmationUrl,
        public readonly ?string $c2bValidationUrl,
        public readonly string $stkPasskey,
        public readonly ?string $stkCallbackUrl,
        public readonly string $stkTransactionType,
        public readonly ?string $stkPartyB,
        public readonly string $statusInitiatorName,
        public readonly string $statusSecurityCredential,
        public readonly ?string $statusResultUrl,
        public readonly ?string $statusTimeoutUrl,
    ) {}

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        $c2b = $config['c2b'] ?? [];
        $stk = $config['stk'] ?? [];
        $status = $config['status'] ?? [];

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
            statusInitiatorName: $status['initiator_name'] ?? '',
            statusSecurityCredential: $status['security_credential'] ?? '',
            statusResultUrl: $status['result_url'] ?? null,
            statusTimeoutUrl: $status['timeout_url'] ?? null,
        );
    }

    public function url(string $path): string
    {
        return $this->baseUrl.$path;
    }
}
