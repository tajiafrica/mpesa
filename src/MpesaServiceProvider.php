<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use TajiAfrica\Mpesa\Client\MpesaClient;
use TajiAfrica\Mpesa\Client\OAuthAuthenticator;
use TajiAfrica\Mpesa\Config\MpesaConfig;

class MpesaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mpesa.php', 'mpesa');

        $this->app->singleton(GuzzleClient::class, fn () => new GuzzleClient);

        $this->app->singleton(MpesaConfig::class, function () {
            $config = config('mpesa');

            Validator::validate($config, [
                'consumer_key' => 'required|string',
                'consumer_secret' => 'required|string',
                'environment' => 'required|in:sandbox,production',
                'shortcode' => 'required|string',
            ]);

            return MpesaConfig::fromArray($config);
        });

        $this->app->singleton(MpesaClient::class, function () {
            $mpesaConfig = app(MpesaConfig::class);
            $http = app(GuzzleClient::class);

            return new MpesaClient(
                $mpesaConfig,
                new OAuthAuthenticator($mpesaConfig, $http),
                $http,
            );
        });

        $this->app->singleton('mpesa', fn () => new Mpesa(app(MpesaClient::class), app(MpesaConfig::class)));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/mpesa.php' => config_path('mpesa.php'),
        ], 'mpesa-config');
    }
}
