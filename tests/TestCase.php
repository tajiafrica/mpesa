<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TajiAfrica\Mpesa\MpesaServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            MpesaServiceProvider::class,
        ];
    }
}
