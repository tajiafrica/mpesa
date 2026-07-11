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
    'shortcode' => '600000',
]));

it('pulls transactions for a date range', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['access_token' => 'tok_1', 'expires_in' => 3600])),
        new Response(200, [], json_encode([
            'ResponseRefID' => '26178-42530161-2',
            'ResponseCode' => '1000',
            'ResponseMessage' => 'Success',
            'Response' => [
                [
                    'transactionId' => 'yzlyrEsRG1',
                    'trxDate' => '2020-08-05T10:13:00Z',
                    'msisdn' => 722000000,
                    'amount' => '168.00',
                ],
            ],
        ])),
    ]);
    $http = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    $response = (new Mpesa($client, $this->config))
        ->pullTransactions()
        ->from('2020-08-04 8:36:00')
        ->to('2020-08-16 10:10:00')
        ->offset(0)
        ->send();

    expect($response->get('ResponseCode'))->toBe('1000')
        ->and($response->get('ResponseMessage'))->toBe('Success');
});

it('requires start date, end date, and offset', function () {
    $http = new GuzzleClient();
    $client = new MpesaClient($this->config, new OAuthAuthenticator($this->config, $http), $http);

    (new Mpesa($client, $this->config))->pullTransactions()->send();
})->throws(MpesaValidationException::class);
