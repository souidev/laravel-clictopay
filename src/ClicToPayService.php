<?php

namespace Souidev\ClicToPayLaravel;


use Illuminate\Support\Facades\Http;
use Exception;
use RuntimeException;

class ClicToPayService
{
    private $config;
    private $baseUrl;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['test_mode']
            ? 'https://test.clictopay.com/payment/rest/'
            : $config['api_base_url'];
    }

    /**
     * Register a payment with ClicToPay.
     *
     * @param array $params
     * @return array|object
     * @throws Exception
     */
    public function registerPayment(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderNumber', 'amount', 'currency', 'returnUrl'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }

        // Optional parameters as per manual
        $optionalParams = [
            'language' => null,           // Payment page language (en, fr, ar)
            'pageView' => null,           // DESKTOP or MOBILE
            'jsonParams' => null,         // Additional parameters in JSON format (including email for notifications)
//            'jsonParams' => json_encode([
//                'email' => 'customer@example.com'
//                // other optional parameters
//            ])
            'expirationDate' => null,     // Order expiration date (ISO 8601)
            'orderDescription' => null,   // Order description
            'merchantLogin' => null,      // Merchant identifier
        ];

        // Merge optional parameters if provided
        $params = array_merge($optionalParams, array_filter($params));

        $url = $this->baseUrl . 'register.do';

        try {
            $response = Http::asForm()->post($url, array_merge($this->config, $params));

            if (!$response->successful()) {
                $data = $response->json();
                throw new Exception($data['errorMessage'] ?? 'Failed to register payment');
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Error registering payment: ' . $e->getMessage());
        }
    }

    /**
     * Register a pre-authorization with ClicToPay.
     *
     * @param array $params
     * @return array|object
     * @throws Exception
     */
    public function registerPreAuth(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderNumber', 'amount', 'currency', 'returnUrl'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }

        $url = $this->baseUrl . 'registerPreAuth.do';
        try {
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();

            if ($response->successful()) {
                return $data;
            }

            throw new RuntimeException($data['errorMessage'] ?? 'Failed to register pre-authorization.');

        } catch (Exception $e) {
            throw new RuntimeException('Error registering pre-authorization: ' . $e->getMessage());
        }
    }

    // Add other methods for:
    // - Confirmation
    // - Annulation de paiement
    // - Remboursement de paiement
    // - Etat d'un paiement
    // - etc.
    /**
     * Confirm a pre-authorized payment.
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function confirmPayment(array $params): array
    {
        $requiredParams = ['userName', 'password', 'orderId', 'amount'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new RuntimeException("Missing required parameter: {$param}");
            }
        }
        $url = $this->baseUrl . 'deposit.do';

        try{
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();
            if ($response->successful()) {
                return $data;
            }

            throw new RuntimeException($data['errorMessage'] ?? 'Failed to confirm payment.');
        } catch (Exception $e) {
            throw new RuntimeException('Error confirming payment: ' . $e->getMessage());
        }

    }

    /**
     * Cancel a payment.
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function cancelPayment(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderId'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new RuntimeException("Missing required parameter: {$param}");
            }
        }
        $url = $this->baseUrl . 'cancel.do';
        try{
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();
            if ($response->successful()) {
                return $data;
            }

            throw new RuntimeException($data['errorMessage'] ?? 'Failed to cancel payment.');
        } catch (Exception $e) {
            throw new RuntimeException('Error canceling payment: ' . $e->getMessage());
        }
    }



    /**
     * Refund a payment.
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function refundPayment(array $params): array
    {
        $requiredParams = ['userName', 'password', 'orderId', 'amount'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new RuntimeException("Missing required parameter: {$param}");
            }
        }
        $url = $this->baseUrl . 'refund.do';
        try{
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();
            if ($response->successful()) {
                return $data;
            }

            throw new RuntimeException($data['errorMessage'] ?? 'Failed to refund payment.');
        } catch (Exception $e) {
            throw new RuntimeException('Error refunding payment: ' . $e->getMessage());
        }

    }

    /**
     * Get the status of a payment.
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getPaymentStatus(array $params): array
    {
        $requiredParams = ['userName', 'password', 'orderId'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new RuntimeException("Missing required parameter: {$param}");
            }
        }

        // Optional parameters
        $optionalParams = [
            'language' => null,  // Response language (en, fr, ar)
        ];

        // Merge optional parameters if provided
        $params = array_merge($optionalParams, array_filter($params));

        $url = $this->baseUrl . 'getOrderStatus.do';

        try {
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();

            if ($response->successful()) {
                return $data;
            }

            throw new RuntimeException($data['errorMessage'] ?? 'Failed to get payment status.');
        } catch (Exception $e) {
            throw new RuntimeException('Error getting payment status: ' . $e->getMessage());
        }
    }

    /**
     * Get extended order status with additional fields
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getExtendedOrderStatus(array $params): array
    {
        $requiredParams = ['userName', 'password', 'orderId'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new RuntimeException("Missing required parameter: {$param}");
            }
        }

        $url = $this->baseUrl . 'getOrderStatusExtended.do';

        try {
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();

            if ($response->successful()) {
                return $data;
            }

            throw new RuntimeException($data['errorMessage'] ?? 'Failed to get extended order status.');
        } catch (Exception $e) {
            throw new RuntimeException('Error getting extended order status: ' . $e->getMessage());
        }
    }
}
