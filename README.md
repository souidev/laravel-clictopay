# Laravel ClicToPay Integration Package

This package simplifies the integration of the ClicToPay payment gateway into your Laravel applications.

## Installation

1.  **Install the package via Composer:**

    ```bash
    composer require your-vendor-name/laravel-clictopay
    ```

## Configuration

1.  **Publish the configuration file:**

    ```bash
    php artisan vendor:publish --provider="YourVendorName\ClicToPayLaravel\ClicToPayLaravelServiceProvider" --tag="config"
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
        CLICTOPAY_RETURN_URL=[https://your-site.com/payment/success](https://your-site.com/payment/success)
        CLICTOPAY_FAIL_URL=[https://your-site.com/payment/fail](https://your-site.com/payment/fail)
        ```

## Usage

1.  **Use the `ClicToPay` facade to interact with the ClicToPay API:**

    ```php
    use YourVendorName\ClicToPayLaravel\Facades\ClicToPay;

    try {
        // Register a payment
        $paymentDetails = ClicToPay::registerPayment([
            'orderNumber' => 'ORDER-12345',
            'amount' => 10000, // in cents
            'currency' => 788, // Currency code (e.g., 788 for TND)
            'returnUrl' => '[https://your-site.com/payment/success](https://your-site.com/payment/success)',
            'failUrl' => '[https://your-site.com/payment/fail](https://your-site.com/payment/fail)',
            'description' => 'Payment for order #12345',
        ]);

        // Handle the response (e.g., redirect the user to the payment form)
        return redirect()->away($paymentDetails['formUrl']);

    } catch (\Exception $e) {
        // Handle the error (e.g., display an error message)
        return back()->with('error', $e->getMessage());
    }
    ```

## Available Methods

Refer to the `ClicToPayService` class for available methods.  Here's a quick overview:

* `registerPayment(array $params)`:  Registers a new payment.
* `registerPreAuth(array $params)`: Registers a pre-authorization.
* `confirmPayment(array $params)`:  Confirms a pre-authorized payment.
* `cancelPayment(array $params)`: Cancels a payment.
* `refundPayment(array $params)`: Refunds a payment.
* `getPaymentStatus(array $params)`: Retrieves the status of a payment.

## Contributing

Please see [CONTRIBUTING.md](https://github.com/your-vendor-name/your-package-name/blob/main/CONTRIBUTING.md) for details.

## License

[MIT License](https://github.com/your-vendor-name/your-package-name/blob/main/LICENSE.md)
