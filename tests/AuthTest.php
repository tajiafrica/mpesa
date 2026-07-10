<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response;
use TajiAfrica\Mpesa\Client\MpesaClient;
use TajiAfrica\Mpesa\Client\OAuthAuthenticator;
use TajiAfrica\Mpesa\Config\MpesaConfig;
use TajiAfrica\Mpesa\Enums\Paths;
use TajiAfrica\Mpesa\Exceptions\MpesaNetworkException;

beforeEach(fn () => $this->config = MpesaConfig::fromArray([
    'consumer_key' => 'key', 'consumer_secret' => 'secret', 'environment' => 'sandbox', 'base_url' => 'https://sandbox.safaricom.co.ke',
]));

it('builds config from array', function () {
    expect($this->config->consumerKey)->toBe('key')
        ->and($this->config->environment)->toBe('sandbox')
        ->and($this->config->baseUrl)->toBe('https://sandbox.safaricom.co.ke');
});

it('fetches and caches oauth token', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
    ]);
    $auth = new OAuthAuthenticator($this->config, new GuzzleClient(['handler' => HandlerStack::create($mock)]));

    expect($auth->getToken())->toBe('tok_1');
});

it('makes authenticated post request', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode(['ResponseCode' => '0'])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    expect($client->post(Paths::OAuthToken, [])->get('ResponseCode'))->toBe('0');
});

it('throws on connection failure', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new ConnectException('refused', new GuzzleRequest('POST', 'test')),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    $client->post(Paths::OAuthToken, []);
})->throws(MpesaNetworkException::class);
