<?php

declare(strict_types=1);

namespace TajiAfrica\Mpesa\Exceptions;

use RuntimeException;

/** Thrown on connection failure to the M-Pesa API (timeout, DNS, TLS). */
class MpesaNetworkException extends RuntimeException {}
