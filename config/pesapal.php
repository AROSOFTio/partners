<?php
if (!function_exists('pesapal_config')) {
    function pesapal_config(string $key, $default = null)
    {
        if (function_exists('config_value')) {
            return config_value('pesapal.' . $key, $default);
        }
        $config = $GLOBALS['config']['pesapal'] ?? [];
        return $config[$key] ?? $default;
    }
}

if (!function_exists('createPesapalPaymentRequest')) {
    function createPesapalPaymentRequest(array $order): array
    {
        $merchantReference = $order['order_code'] ?? ('ORDER-' . time());
        try {
            $trackingId = bin2hex(random_bytes(8));
        } catch (Exception $e) {
            $trackingId = uniqid('psp_', true);
        }

        $iframeUrl = (string)pesapal_config('iframe_url', '');
        $demo = (bool)pesapal_config('demo', true);
        if (!$demo && $iframeUrl === '') {
            throw new Exception('Pesapal iframe URL not configured.');
        }

        return [
            'merchant_reference' => $merchantReference,
            'tracking_id' => $trackingId,
            'iframe_url' => $iframeUrl,
        ];
    }
}

if (!function_exists('verifyPesapalPayment')) {
    function verifyPesapalPayment(string $trackingId, string $orderCode): array
    {
        $demo = (bool)pesapal_config('demo', true);
        if ($demo) {
            return [
                'status' => 'COMPLETED',
                'payment_method' => 'demo',
            ];
        }

        $verifyUrl = (string)pesapal_config('verify_url', '');
        if ($verifyUrl === '') {
            throw new Exception('Pesapal verification URL not configured.');
        }

        // TODO: Integrate real Pesapal verification call.
        return [
            'status' => 'PENDING',
        ];
    }
}
