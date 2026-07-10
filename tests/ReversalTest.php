<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use TajiAfrica\Mpesa\Client\MpesaClient;
use TajiAfrica\Mpesa\Client\OAuthAuthenticator;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Exceptions\MpesaValidationException;
use TajiAfrica\Mpesa\Mpesa;

beforeEach(fn () => $this->config = MpesaConfig::fromArray([
    'consumer_key' => 'key',
    'consumer_secret' => 'secret',
    'environment' => 'sandbox',
    'base_url' => 'https://sandbox.safaricom.co.ke',
    'shortcode' => '600782',
    'initiator_name' => 'testinitiator',
    'security_credential' => 'testcredential',
    'reversal' => [
        'result_url' => 'https://example.com/result',
        'timeout_url' => 'https://example.com/timeout',
    ],
]));

it('sends reversal request', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode([
            'ResponseCode' => '0',
            'ResponseDescription' => 'Accept the service request successfully.',
        ])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    $response = (new Mpesa($client, $this->config))
        ->reversal()
        ->transactionId('PDU91HIVIT')
        ->amount(200)
        ->remarks('Payment reversal')
        ->send();

    expect($response->successful())->toBeTrue();
});

it('requires transaction id', function () {
    $http = new GuzzleClient();
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    (new Mpesa($client, $this->config))
        ->reversal()
        ->amount(100)
        ->send();
})->throws(MpesaValidationException::class);
