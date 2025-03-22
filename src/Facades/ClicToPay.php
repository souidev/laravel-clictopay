<?php

namespace Souidev\ClicToPayLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class ClicToPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ClicToPay'; // This is the key we bound in the Service Provider
    }
}
