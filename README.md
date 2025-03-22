# Laravel ClicToPay Integration Package

This package simplifies the integration of the ClicToPay payment gateway into your Laravel applications.

## Installation

1.  **Install the package via Composer:**

    ```bash
    composer require Souidev/laravel-clictopay
    ```

## Configuration

1.  **Publish the configuration file:**

    ```bash
    php artisan vendor:publish --provider="Souidev\ClicToPayLaravel\ClicToPayLaravelServiceProvider" --tag="config"
    ```

2.  **Configure your ClicToPay credentials:**

    * Edit the `config/clictopay.php` file.  It's recommended to use environment variables for sensitive information:

        ```php
        // config/clictopay.php
        return [
            'username' => env('CLICTOPAY_USERNAME'),
            'password' => env('CLICTOPAY_PASSWORD'),
            'test_mode' => env('CLICTOPAY_TEST_MODE', true),
            'return_url' => env('CLICTOPAY_RETURN_URL'),
            'fail_url' => env('CLICTOPAY_FAIL_URL'),
            'api_base_url' => env('CLICTOPAY_API_BASE_URL', '[https://test.clictopay.com/payment/rest/](https://test.clictopay.com/payment/rest/)'), // Default test URL
        ];
        ```

    * Set the corresponding environment variables in your `.env` file:

        ```
        CLICTOPAY_USERNAME=your_username
        CLICTOPAY_PASSWORD=your_password
        CLICTOPAY_TEST_MODE=true  # or false
        CLICTOPAY_RETURN_URL=https://your-site.com/payment/success
        CLICTOPAY_FAIL_URL=https://your-site.com/payment/fail
        ```

## Usage

1. **Use the `ClicToPay` facade to interact with the ClicToPay API:**

    ```php
    use Souidev\ClicToPayLaravel\Facades\ClicToPay;

    try {
        // Register a payment with optional parameters
        $paymentDetails = ClicToPay::registerPayment([
            // Required parameters
            'orderNumber' => 'ORDER-12345',
            'amount' => 10000, // in cents
            'currency' => 788, // Currency code (788 for TND)
            
            // Optional parameters
            'language' => 'fr',           // fr, en, ar
            'pageView' => 'DESKTOP',      // DESKTOP or MOBILE
            'orderDescription' => 'Payment for order #12345',
            'expirationDate' => '2024-12-31T23:59:59', // ISO 8601
            'jsonParams' => json_encode([
                'email' => 'customer@example.com',  // Required if merchant notifications are enabled
                'orderNumber' => '1234567890',      // Your internal order reference
                // Add any additional parameters needed for bank processing
            ])
        ]);

        // Redirect user to ClicToPay payment page
        return redirect()->away($paymentDetails['formUrl']);

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
    ```

2. **Check payment status:**

    ```php
    try {
        $status = ClicToPay::getPaymentStatus([
            'orderId' => 'ORDER-12345',
            'language' => 'fr' // Optional - for localized error messages
        ]);
        
        // Handle the response
        if ($status['ErrorCode'] === '0') { // No system error
            // Process the payment status
            $orderStatus = $status['OrderStatus'];
            // Possible values in $orderStatus:
            // - 0: Order registered but not paid
            // - 1: Order pre-authorized
            // - 2: Order paid
            // - 3: Authorization canceled
            // - 4: Transaction canceled
            // - 5: Authorization on hold
            // - 6: Refund
        }
    } catch (\Exception $e) {
        // Handle error
    }
    ```

3. **Get extended order status:**

    ```php
    try {
        $extendedStatus = ClicToPay::getExtendedOrderStatus([
            'orderId' => 'ORDER-12345',
            'language' => 'fr' // Optional - for localized error messages
        ]);
        
        // Handle extended status information
        if ($extendedStatus['ErrorCode'] === '0') {
            // Access additional payment information
            $orderStatus = $extendedStatus['orderStatus'];
            $amount = $extendedStatus['amount'];
            $currency = $extendedStatus['currency'];
            $date = $extendedStatus['date'];
            // ... other available fields
        }
    } catch (\Exception $e) {
        // Handle error
    }
    ```

The package automatically handles test/live mode based on your configuration:
- Test mode: Set `CLICTOPAY_TEST_MODE=true` in your `.env` file (uses `https://test.clictopay.com/payment/rest/`)
- Production mode: Set `CLICTOPAY_TEST_MODE=false` (uses `https://clictopay.com/payment/rest/` or other provided URL)

**Note:** When customer notification is enabled for your merchant account, you must include the customer's email address in the `jsonParams` field as shown in the example above.

## Available Methods

Refer to the `ClicToPayService` class for available methods.  Here's a quick overview:

* `registerPayment(array $params)`:  Registers a new payment.
* `registerPreAuth(array $params)`: Registers a pre-authorization.
* `confirmPayment(array $params)`:  Confirms a pre-authorized payment.
* `cancelPayment(array $params)`: Cancels a payment.
* `refundPayment(array $params)`: Refunds a payment.
* `getPaymentStatus(array $params)`: Retrieves the status of a payment.

## Contributing

Please see [CONTRIBUTING.md](https://github.com/souidev/laravel-clictopay/blob/main/CONTRIBUTING.md) for details.

## License

[MIT License](https://github.com/souidev/laravel-clictopay/blob/main/LICENSE.md)
