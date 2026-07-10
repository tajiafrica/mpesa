<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Enums;

enum Paths: string
{
    case OAuthToken = '/oauth/v1/generate?grant_type=client_credentials';
    case C2BRegisterUrls = '/mpesa/c2b/v1/registerurl';
}
