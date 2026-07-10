<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Facades;

use Illuminate\Support\Facades\Facade;

class Mpesa extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'mpesa';
    }
}
