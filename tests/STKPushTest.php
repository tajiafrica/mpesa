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
    'shortcode' => '174379',
    'stk' => [
        'passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',
        'callback_url' => 'https://example.com/callback',
    ],
]));

it('sends stk push and returns checkout request id', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode([
            'MerchantRequestID' => '2654-4b64-97ff-b827b542881d3130',
            'CheckoutRequestID' => 'ws_CO_1007202409152617172396192',
            'ResponseCode' => '0',
            'ResponseDescription' => 'Success. Request accepted for processing',
            'CustomerMessage' => 'Success. Request accepted for processing',
        ])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    $response = (new Mpesa($client, $this->config))
        ->stkPush()
        ->amount(100)
        ->phone('254708374149')
        ->reference('INV-001')
        ->description('Payment')
        ->send();

    expect($response->successful())->toBeTrue()
        ->and($response->get('CheckoutRequestID'))->toBe('ws_CO_1007202409152617172396192')
        ->and($response->get('MerchantRequestID'))->toBe('2654-4b64-97ff-b827b542881d3130');
});

it('requires amount, phone, and reference', function () {
    $http = new GuzzleClient();
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    (new Mpesa($client, $this->config))->stkPush()->send();
})->throws(MpesaValidationException::class);

it('queries stk push status', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode([
            'ResponseCode' => '0',
            'ResultDesc' => 'The service request is processed successfully.',
        ])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    $response = (new Mpesa($client, $this->config))
        ->stkPushQuery('ws_CO_1007202409152617172396192');

    expect($response->successful())->toBeTrue()
        ->and($response->get('ResultDesc'))->toBe('The service request is processed successfully.');
});
