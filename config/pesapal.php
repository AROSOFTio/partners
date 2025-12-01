<?php
if (!isset($GLOBALS['config'])) {
    $configPath = file_exists(__DIR__ . '/config.php') ? __DIR__ . '/config.php' : __DIR__ . '/config.example.php';
    $GLOBALS['config'] = require $configPath;
}

function getPesapalConfig(): array
{
    return $GLOBALS['config']['pesapal'] ?? [];
}

function pesapalApiBase(): string
{
    $config = getPesapalConfig();
    return rtrim($config['api_base'] ?? 'https://pay.pesapal.com/v3/api', '/');
}

function getPesapalToken(): string
{
    static $token;
    static $expiresAt = 0;

    if ($token && $expiresAt > time() + 60) {
        return $token;
    }

    $config = getPesapalConfig();
    $body = [
        'consumer_key' => $config['consumer_key'] ?? '',
        'consumer_secret' => $config['consumer_secret'] ?? '',
    ];

    $url = pesapalApiBase() . '/Auth/RequestToken';
    $response = pesapalCurl('POST', $url, $body, false);
    if (!isset($response['token'])) {
        throw new Exception('Unable to obtain Pesapal token');
    }
    $token = $response['token'];
    $expiresAt = time() + (int)($response['expires_in'] ?? 3000);
    return $token;
}

function pesapalCurl(string $method, string $url, array $data = [], bool $auth = true): array
{
    $ch = curl_init();
    $headers = ['Content-Type: application/json'];
    if ($auth) {
        $headers[] = 'Authorization: Bearer ' . getPesapalToken();
    }

    if ($method === 'GET' && !empty($data)) {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($data);
    }

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    if ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $result = curl_exec($ch);
    if ($result === false) {
        throw new Exception('Pesapal request error: ' . curl_error($ch));
    }
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($result, true);
    if ($status >= 400) {
        $message = $decoded['message'] ?? $result;
        throw new Exception('Pesapal API error (' . $status . '): ' . $message);
    }
    return is_array($decoded) ? $decoded : [];
}

function pesapalRegisterIPN(): ?string
{
    $config = getPesapalConfig();
    if (!empty($config['ipn_id'])) {
        return $config['ipn_id'];
    }
    $ipnUrl = $config['ipn_url'] ?? ($config['callback_url'] ?? '');
    if (!$ipnUrl) {
        return null;
    }
    $payload = [
        'url' => $ipnUrl,
        'ipn_notification_type' => 'GET',
    ];
    $response = pesapalCurl('POST', pesapalApiBase() . '/URLSetup/RegisterIPN', $payload, true);
    return $response['ipn_id'] ?? null;
}

function createPesapalPaymentRequest(array $order): array
{
    $config = getPesapalConfig();
    $ipnId = pesapalRegisterIPN();
    $callbackUrl = $config['callback_url'] ?? (($GLOBALS['config']['base_url'] ?? '') . '/payment/callback');
    $body = [
        'id' => $order['order_code'],
        'currency' => $order['currency'],
        'amount' => number_format((float)$order['amount_due_now'], 2, '.', ''),
        'description' => $config['description'] ?? ('BenTech collaboration order ' . $order['order_code']),
        'callback_url' => $callbackUrl,
        'notification_id' => $ipnId,
        'branch' => 'online',
        'billing_address' => [
            'email_address' => $order['customer_email'] ?? '',
            'phone_number' => '',
            'country_code' => 'UG',
            'first_name' => $order['customer_name'] ?? '',
            'middle_name' => '',
            'last_name' => '',
            'line_1' => $order['company_name'] ?? '',
            'line_2' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'zip_code' => '',
        ],
    ];

    $response = pesapalCurl('POST', pesapalApiBase() . '/Transactions/SubmitOrderRequest', $body, true);
    return [
        'iframe_url' => $response['redirect_url'] ?? '',
        'merchant_reference' => $order['order_code'],
        'tracking_id' => $response['order_tracking_id'] ?? '',
    ];
}

function verifyPesapalPayment(string $trackingId, string $reference): array
{
    $params = [
        'orderTrackingId' => $trackingId,
        'orderMerchantReference' => $reference,
    ];
    $response = pesapalCurl('GET', pesapalApiBase() . '/Transactions/GetTransactionStatus', $params, true);
    return [
        'status' => strtolower($response['status'] ?? 'failed'),
        'payment_method' => $response['payment_method'] ?? null,
        'amount' => $response['amount'] ?? null,
        'currency' => $response['currency'] ?? null,
    ];
}
