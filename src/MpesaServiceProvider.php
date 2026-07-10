<?php

namespace TajiAfrica\Mpesa;

use Illuminate\Support\ServiceProvider;

class MpesaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('mpesa', fn ($app) => new Mpesa($app['config']->get('mpesa')));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/mpesa.php' => config_path('mpesa.php'),
        ], 'mpesa-config');
    }
}
