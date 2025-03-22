<?php

return [
    'username' => env('CLICTOPAY_USERNAME'),
    'password' => env('CLICTOPAY_PASSWORD'),
    'test_mode' => env('CLICTOPAY_TEST_MODE', true), // Added test mode
    'return_url' => env('CLICTOPAY_RETURN_URL'),
    'fail_url' => env('CLICTOPAY_FAIL_URL'),
    // Add other configuration options as needed, like URLs, currency, etc.
    'api_base_url' => env('CLICTOPAY_API_BASE_URL', 'https://test.clictopay.com/payment/rest/'), // Default to test URL
];
