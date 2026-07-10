<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use TajiAfrica\Mpesa\Client\MpesaClient;
use TajiAfrica\Mpesa\Client\OAuthAuthenticator;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Mpesa;

it('registers c2b urls and returns response', function () {
    $config = MpesaConfig::fromArray([
        'consumer_key' => 'key',
        'consumer_secret' => 'secret',
        'environment' => 'sandbox',
        'base_url' => 'https://sandbox.safaricom.co.ke',
        'c2b' => [
            'shortcode' => '600984',
            'confirmation_url' => 'https://example.com/confirmation',
            'validation_url' => 'https://example.com/validation',
        ],
    ]);

    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode([
            'ResponseCode' => '0',
            'ResponseDescription' => 'Success',
        ])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($config, new OAuthAuthenticator($config, $http), $http);

    $response = (new Mpesa($client, $config))->registerC2BUrls();

    expect($response->successful())->toBeTrue()
        ->and($response->get('ResponseDescription'))->toBe('Success');
});
