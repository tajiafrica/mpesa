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

it('registers pull and returns response', function () {
    $config = MpesaConfig::fromArray([
        'consumer_key' => 'key',
        'consumer_secret' => 'secret',
        'environment' => 'sandbox',
        'base_url' => 'https://sandbox.safaricom.co.ke',
        'shortcode' => '600000',
        'pull' => [
            'nominated_number' => '254722000000',
            'callback_url' => 'https://example.com/pull/callback',
        ],
    ]);

    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode([
            'ResponseRefID' => 'feb5e3f2-fbc-4745-844c-ee37b546f627',
            'ResponseStatus' => '1000',
            'ShortCode' => '600000',
            'ResponseDescription' => 'Shortcode Registered Successfully',
        ])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($config, new OAuthAuthenticator($config, $http), $http);

    $response = (new Mpesa($client, $config))->registerPull();

    expect($response->get('ResponseRefID'))->toBe('feb5e3f2-fbc-4745-844c-ee37b546f627')
        ->and($response->get('ResponseStatus'))->toBe('1000');
});
