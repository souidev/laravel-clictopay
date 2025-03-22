<?php

namespace Souidev\ClicToPayLaravel;


use Illuminate\Support\Facades\Http;
use Exception;

class ClicToPayService
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Register a payment with ClicToPay.
     *
     * @param array $params
     * @return array|object
     * @throws \Exception
     */
    public function registerPayment(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderNumber', 'amount', 'currency', 'returnUrl'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }
        $url = $this->config['api_base_url'] . 'register.do';
        try {
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();

            if ($response->successful()) {
                return $data;
            }
            else{
                throw new Exception($data['errorMessage'] ?? 'Failed to register payment.');
            }

        } catch (\Exception $e) {
            throw new Exception('Error registering payment: ' . $e->getMessage());
        }
    }

    /**
     * Register a pre-authorization with ClicToPay.
     *
     * @param array $params
     * @return array|object
     * @throws \Exception
     */
    public function registerPreAuth(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderNumber', 'amount', 'currency', 'returnUrl'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }

        $url = $this->config['api_base_url'] . 'registerPreAuth.do';
        try {
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();

            if ($response->successful()) {
                return $data;
            }
            else{
                throw new Exception($data['errorMessage'] ?? 'Failed to register pre-authorization.');
            }

        } catch (\Exception $e) {
            throw new Exception('Error registering pre-authorization: ' . $e->getMessage());
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
     * @throws \Exception
     */
    public function confirmPayment(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderId', 'amount'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }
        $url = $this->config['api_base_url'] . 'deposit.do';

        try{
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();
            if ($response->successful()) {
                return $data;
            }
            else{
                throw new Exception($data['errorMessage'] ?? 'Failed to confirm payment.');
            }
        } catch (\Exception $e) {
            throw new Exception('Error confirming payment: ' . $e->getMessage());
        }

    }

    /**
     * Cancel a payment.
     *
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function cancelPayment(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderId'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }
        $url = $this->config['api_base_url'] . 'cancel.do';
        try{
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();
            if ($response->successful()) {
                return $data;
            }
            else{
                throw new Exception($data['errorMessage'] ?? 'Failed to cancel payment.');
            }
        } catch (\Exception $e) {
            throw new Exception('Error canceling payment: ' . $e->getMessage());
        }
    }



    /**
     * Refund a payment.
     *
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function refundPayment(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderId', 'amount'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }
        $url = $this->config['api_base_url'] . 'refund.do';
        try{
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();
            if ($response->successful()) {
                return $data;
            }
            else{
                throw new Exception($data['errorMessage'] ?? 'Failed to refund payment.');
            }
        } catch (\Exception $e) {
            throw new Exception('Error refunding payment: ' . $e->getMessage());
        }

    }

    /**
     * Get the status of a payment.
     *
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function getPaymentStatus(array $params)
    {
        $requiredParams = ['userName', 'password', 'orderId'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }
        $url = $this->config['api_base_url'] . 'getOrderStatus.do';
        try {
            $response = Http::asForm()->post($url, array_merge($this->config, $params));
            $data = $response->json();
            if ($response->successful()) {
                return $data;
            }
            else{
                throw new Exception($data['errorMessage'] ?? 'Failed to get payment status.');
            }

        } catch (\Exception $e) {
            throw new Exception('Error getting payment status: ' . $e->getMessage());
        }
    }
}
