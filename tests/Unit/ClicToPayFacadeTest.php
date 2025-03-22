<?php

use Souidev\ClicToPayLaravel\Facades\ClicToPay;

it('resolves the facade correctly', function () {
    expect(ClicToPay::getFacadeRoot())
        ->toBeInstanceOf(\Souidev\ClicToPayLaravel\ClicToPayService::class);
});