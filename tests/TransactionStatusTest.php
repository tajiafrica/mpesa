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
    'status' => [
        'initiator_name' => 'testinitiator',
        'security_credential' => 'testcredential',
        'result_url' => 'https://example.com/result',
        'timeout_url' => 'https://example.com/timeout',
    ],
]));

it('sends transaction status query by transaction id', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode([
            'OriginatorConversationID' => '1236-7134259-1',
            'ResponseCode' => '0',
            'ResponseDescription' => 'Accept the service request successfully.',
        ])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    $response = (new Mpesa($client, $this->config))
        ->transactionStatus()
        ->transactionId('NEF61H8J60')
        ->send();

    expect($response->successful())->toBeTrue();
});

it('sends transaction status query by original conversation id', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode(['ResponseCode' => '0'])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    $response = (new Mpesa($client, $this->config))
        ->transactionStatus()
        ->originalConversationId('7071-4170-...')
        ->send();

    expect($response->successful())->toBeTrue();
});

it('requires transaction id or original conversation id', function () {
    $http = new GuzzleClient();
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    (new Mpesa($client, $this->config))
        ->transactionStatus()
        ->send();
})->throws(MpesaValidationException::class);
