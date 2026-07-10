<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Enums;

/** Daraja API endpoint paths. */
enum Paths: string
{
    case OAuthToken = '/oauth/v1/generate?grant_type=client_credentials';
    case C2BRegisterUrls = '/mpesa/c2b/v1/registerurl';
    case STKPush = '/mpesa/stkpush/v1/processrequest';
    case STKPushQuery = '/mpesa/stkpush/v1/query';
    case TransactionStatus = '/mpesa/transactionstatus/v1/query';
    case Reversal = '/mpesa/reversal/v1/request';
}
