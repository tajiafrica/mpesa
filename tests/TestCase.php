<?php

namespace TajiAfrica\Mpesa\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
  protected function getPackageProviders($app): array
  {
    return [
      \TajiAfrica\Mpesa\MpesaServiceProvider::class,
    ];
  }
}
