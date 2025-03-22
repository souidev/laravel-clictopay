<?php

namespace Souidev\ClicToPayLaravel\Tests\Unit;

use Souidev\ClicToPayLaravel\ClicToPayService;
use Souidev\ClicToPayLaravel\Facades\ClicToPay;

it('resolves the facade correctly', function () {
    $facade = ClicToPay::getFacadeRoot();

    expect($facade)
        ->toBeInstanceOf(ClicToPayService::class);
});

it('can be used to register a payment', function () {
    $params = [
        'userName' => 'test_user',
        'password' => 'test_pass',
        'orderNumber' => '123',
        'amount' => 1000,
        'currency' => 'TND',
        'returnUrl' => 'https://example.com/return'
    ];

    \Illuminate\Support\Facades\Http::fake([
        '*' => \Illuminate\Support\Facades\Http::response([
            'orderId' => '123456',
            'formUrl' => 'https://test.clictopay.com/payment/form/123456'
        ], 200)
    ]);

    $response = ClicToPay::registerPayment($params);

    expect($response)
        ->toBeArray()
        ->toHaveKey('orderId')
        ->toHaveKey('formUrl');
});
