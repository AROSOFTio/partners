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

if (!function_exists('pesapal_is_demo')) {
    function pesapal_is_demo(): bool
    {
        return (bool)pesapal_config('demo', false);
    }
}

if (!function_exists('pesapal_base_url')) {
    function pesapal_base_url(): string
    {
        $demo = pesapal_is_demo();
        $base = $demo ? 'https://cybqa.pesapal.com/pesapalv3' : 'https://pay.pesapal.com/v3';
        return rtrim($base, '/');
    }
}

if (!function_exists('pesapal_callback_url')) {
    function pesapal_callback_url(): string
    {
        $callback = trim((string)pesapal_config('callback_url', ''));
        if ($callback !== '') {
            return $callback;
        }
        if (function_exists('base_url')) {
            return base_url('payment/callback');
        }
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . '/payment/callback';
    }
}

if (!function_exists('pesapal_http_request')) {
    function pesapal_http_request(string $method, string $url, ?string $token = null, ?array $payload = null): array
    {
        $method = strtoupper($method);
        $headers = ['Accept: application/json'];
        $body = null;

        if ($method === 'GET' && !empty($payload)) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($payload);
        } elseif ($payload !== null) {
            $body = json_encode($payload);
            $headers[] = 'Content-Type: application/json';
        }

        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            if ($body !== null && $method !== 'GET') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
            $response = curl_exec($ch);
            $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                throw new Exception('Pesapal request failed: ' . $error);
            }
            curl_close($ch);
        } else {
            $context = [
                'http' => [
                    'method' => $method,
                    'header' => implode("\r\n", $headers),
                    'timeout' => 30,
                ],
            ];
            if ($body !== null && $method !== 'GET') {
                $context['http']['content'] = $body;
            }
            $response = @file_get_contents($url, false, stream_context_create($context));
            $httpCode = 0;
            if (isset($http_response_header)) {
                foreach ($http_response_header as $line) {
                    if (preg_match('#HTTP/\S+\s+(\d{3})#', $line, $matches)) {
                        $httpCode = (int)$matches[1];
                        break;
                    }
                }
            }
        }

        $data = json_decode((string)$response, true);
        if (!is_array($data)) {
            throw new Exception('Pesapal response was not valid JSON.');
        }
        if ($httpCode >= 400) {
            $message = $data['error']['message'] ?? $data['message'] ?? 'Pesapal request failed.';
            throw new Exception($message);
        }
        if (isset($data['error']['message'])) {
            throw new Exception((string)$data['error']['message']);
        }
        return $data;
    }
}

if (!function_exists('pesapal_auth_token')) {
    function pesapal_auth_token(): string
    {
        $consumerKey = trim((string)pesapal_config('consumer_key', ''));
        $consumerSecret = trim((string)pesapal_config('consumer_secret', ''));
        if ($consumerKey === '' || $consumerSecret === '') {
            throw new Exception('Pesapal consumer_key/consumer_secret not configured.');
        }
        $url = pesapal_base_url() . '/api/Auth/RequestToken';
        $data = pesapal_http_request('POST', $url, null, [
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
        ]);
        $token = $data['token'] ?? '';
        if ($token === '') {
            throw new Exception('Pesapal token missing in response.');
        }
        return $token;
    }
}

if (!function_exists('pesapal_resolve_notification_id')) {
    function pesapal_resolve_notification_id(string $token): string
    {
        $notificationId = trim((string)pesapal_config('notification_id', ''));
        if ($notificationId === '') {
            $notificationId = trim((string)pesapal_config('ipn_id', ''));
        }
        if ($notificationId !== '') {
            return $notificationId;
        }

        $ipnUrl = trim((string)pesapal_config('ipn_url', ''));
        if ($ipnUrl === '') {
            throw new Exception('Pesapal notification_id/ipn_id not configured.');
        }
        $notificationType = strtoupper((string)pesapal_config('ipn_notification_type', 'GET'));

        $listUrl = pesapal_base_url() . '/api/URLSetup/GetIpnList';
        $ipnList = pesapal_http_request('GET', $listUrl, $token);
        if (is_array($ipnList)) {
            foreach ($ipnList as $ipn) {
                if (!empty($ipn['url']) && $ipn['url'] === $ipnUrl && !empty($ipn['ipn_id'])) {
                    return (string)$ipn['ipn_id'];
                }
            }
        }

        $registerUrl = pesapal_base_url() . '/api/URLSetup/RegisterIPN';
        $registered = pesapal_http_request('POST', $registerUrl, $token, [
            'url' => $ipnUrl,
            'ipn_notification_type' => $notificationType,
        ]);
        $ipnId = $registered['ipn_id'] ?? '';
        if ($ipnId === '') {
            throw new Exception('Unable to register Pesapal IPN URL.');
        }
        return $ipnId;
    }
}

if (!function_exists('pesapal_build_billing_address')) {
    function pesapal_build_billing_address(array $order): array
    {
        $fullName = trim((string)($order['customer_name'] ?? 'Customer'));
        $parts = preg_split('/\s+/', $fullName, 2);
        $first = $parts[0] ?? 'Customer';
        $last = $parts[1] ?? '';

        $email = trim((string)($order['customer_email'] ?? ''));
        if ($email === '') {
            throw new Exception('Customer email is required for Pesapal billing address.');
        }

        $billing = [
            'email_address' => $email,
            'phone_number' => trim((string)($order['customer_phone'] ?? '')),
            'country_code' => trim((string)($order['country_code'] ?? '')),
            'first_name' => $first,
            'last_name' => $last,
        ];

        return array_filter($billing, static fn($value) => $value !== '');
    }
}

if (!function_exists('createPesapalPaymentRequest')) {
    function createPesapalPaymentRequest(array $order): array
    {
        $merchantReference = $order['order_code'] ?? ('ORDER-' . time());
        $amount = isset($order['amount_due_now']) ? (float)$order['amount_due_now'] : (float)($order['total_amount'] ?? 0);
        if ($amount <= 0) {
            throw new Exception('Order amount is invalid.');
        }

        $token = pesapal_auth_token();
        $notificationId = pesapal_resolve_notification_id($token);
        $payload = [
            'id' => $merchantReference,
            'currency' => $order['currency'] ?? 'UGX',
            'amount' => round($amount, 2),
            'description' => 'BenTech Collaboration ' . $merchantReference,
            'callback_url' => pesapal_callback_url(),
            'notification_id' => $notificationId,
            'billing_address' => pesapal_build_billing_address($order),
        ];

        $submitUrl = pesapal_base_url() . '/api/Transactions/SubmitOrderRequest';
        $response = pesapal_http_request('POST', $submitUrl, $token, $payload);

        $trackingId = $response['order_tracking_id'] ?? '';
        $redirectUrl = $response['redirect_url'] ?? '';
        $merchantRef = $response['merchant_reference'] ?? $merchantReference;

        if ($trackingId === '' || $redirectUrl === '') {
            throw new Exception('Pesapal did not return a redirect URL.');
        }

        return [
            'merchant_reference' => $merchantRef,
            'tracking_id' => $trackingId,
            'iframe_url' => $redirectUrl,
            'redirect_url' => $redirectUrl,
        ];
    }
}

if (!function_exists('verifyPesapalPayment')) {
    function verifyPesapalPayment(string $trackingId, string $orderCode): array
    {
        if ($trackingId === '') {
            throw new Exception('Missing Pesapal tracking ID.');
        }
        $token = pesapal_auth_token();
        $verifyUrl = pesapal_base_url() . '/api/Transactions/GetTransactionStatus';
        $response = pesapal_http_request('GET', $verifyUrl, $token, [
            'orderTrackingId' => $trackingId,
        ]);
        return $response;
    }
}
