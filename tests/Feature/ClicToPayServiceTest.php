<?php

use Illuminate\Support\Facades\Http;
use Souidev\ClicToPayLaravel\ClicToPayService;

beforeEach(function () {
    $this->config = [
        'api_base_url' => 'https://test.clictopay.com/payment/rest/',
        'username' => 'test_user',
        'password' => 'test_pass',
    ];
    
    $this->service = new ClicToPayService($this->config);
    
    $this->validParams = [
        'userName' => 'test_user',
        'password' => 'test_pass',
        'orderNumber' => '123',
        'amount' => 1000,
        'currency' => 'TND',
        'returnUrl' => 'https://example.com/return'
    ];
});

it('can register a payment successfully', function () {
    Http::fake([
        '*' => Http::response([
            'orderId' => '123456',
            'formUrl' => 'https://test.clictopay.com/payment/form/123456'
        ], 200)
    ]);

    $response = $this->service->registerPayment($this->validParams);

    expect($response)
        ->toBeArray()
        ->toHaveKey('orderId')
        ->toHaveKey('formUrl');
});

it('throws exception when required parameters are missing for payment registration', function () {
    $invalidParams = array_diff_key($this->validParams, ['orderNumber' => '']);
    
    $this->service->registerPayment($invalidParams);
})->throws(Exception::class, 'Missing required parameter: orderNumber');

it('can register a pre-authorization successfully', function () {
    Http::fake([
        '*' => Http::response([
            'orderId' => '123456',
            'formUrl' => 'https://test.clictopay.com/payment/form/123456'
        ], 200)
    ]);

    $response = $this->service->registerPreAuth($this->validParams);

    expect($response)
        ->toBeArray()
        ->toHaveKey('orderId')
        ->toHaveKey('formUrl');
});

it('can confirm a payment successfully', function () {
    $confirmParams = [
        'userName' => 'test_user',
        'password' => 'test_pass',
        'orderId' => '123456',
        'amount' => 1000
    ];

    Http::fake([
        '*' => Http::response([
            'errorCode' => '0',
            'errorMessage' => 'Success'
        ], 200)
    ]);

    $response = $this->service->confirmPayment($confirmParams);

    expect($response)
        ->toBeArray()
        ->toHaveKey('errorCode')
        ->toHaveKey('errorMessage');
});

it('can cancel a payment successfully', function () {
    $cancelParams = [
        'userName' => 'test_user',
        'password' => 'test_pass',
        'orderId' => '123456'
    ];

    Http::fake([
        '*' => Http::response([
            'errorCode' => '0',
            'errorMessage' => 'Success'
        ], 200)
    ]);

    $response = $this->service->cancelPayment($cancelParams);

    expect($response)
        ->toBeArray()
        ->toHaveKey('errorCode')
        ->toHaveKey('errorMessage');
});

it('can refund a payment successfully', function () {
    $refundParams = [
        'userName' => 'test_user',
        'password' => 'test_pass',
        'orderId' => '123456',
        'amount' => 1000
    ];

    Http::fake([
        '*' => Http::response([
            'errorCode' => '0',
            'errorMessage' => 'Success'
        ], 200)
    ]);

    $response = $this->service->refundPayment($refundParams);

    expect($response)
        ->toBeArray()
        ->toHaveKey('errorCode')
        ->toHaveKey('errorMessage');
});

it('can get payment status successfully', function () {
    $statusParams = [
        'userName' => 'test_user',
        'password' => 'test_pass',
        'orderId' => '123456'
    ];

    Http::fake([
        '*' => Http::response([
            'OrderStatus' => 2,
            'ErrorCode' => '0',
            'ErrorMessage' => 'Success'
        ], 200)
    ]);

    $response = $this->service->getPaymentStatus($statusParams);

    expect($response)
        ->toBeArray()
        ->toHaveKey('OrderStatus')
        ->toHaveKey('ErrorCode')
        ->toHaveKey('ErrorMessage');
});

it('handles API errors appropriately', function () {
    Http::fake([
        '*' => Http::response([
            'errorCode' => '100',
            'errorMessage' => 'Invalid credentials'
        ], 400)
    ]);

    $this->service->registerPayment($this->validParams);
})->throws(Exception::class, 'Failed to register payment');

it('handles network errors appropriately', function () {
    Http::fake([
        '*' => Http::response(null, 500)
    ]);

    $this->service->registerPayment($this->validParams);
})->throws(Exception::class, 'Error registering payment');