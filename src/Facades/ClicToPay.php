<?php

namespace Souidev\ClicToPayLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array registerPayment(array $params)
 * @method static array getPaymentStatus(array $params)
 * @method static array confirmPayment(array $params)
 * @method static array cancelPayment(array $params)
 * @method static array refundPayment(array $params)
 * @method static array registerPreAuth(array $params)
 */
class ClicToPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ClicToPay';
    }
}
