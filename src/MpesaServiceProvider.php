<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use TajiAfrica\Mpesa\Client\MpesaClient;
use TajiAfrica\Mpesa\Client\OAuthAuthenticator;
use TajiAfrica\Mpesa\Config\MpesaConfig;

/** Laravel service provider that wires the M-Pesa SDK singletons. */
class MpesaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mpesa.php', 'mpesa');

        $this->app->singleton(GuzzleClient::class, fn () => new GuzzleClient);

        $this->app->singleton(MpesaConfig::class, fn () => MpesaConfig::fromArray(config('mpesa')));

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

    /** Publish the config file for user customization. */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/mpesa.php' => config_path('mpesa.php'),
        ], 'mpesa-config');
    }
}
