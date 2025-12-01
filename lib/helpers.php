<?php
function config_value($key = null, $default = null)
{
    $config = $GLOBALS['config'] ?? [];
    if ($key === null) {
        return $config;
    }
    $segments = explode('.', $key);
    $value = $config;
    foreach ($segments as $segment) {
        if (is_array($value) && array_key_exists($segment, $value)) {
            $value = $value[$segment];
        } else {
            return $default;
        }
    }
    return $value;
}

function base_url(string $path = ''): string
{
    $base = rtrim(config_value('base_url', ''), '/');
    return $base . '/' . ltrim($path, '/');
}

function is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function view(string $view, array $data = [], string $layout = 'layouts/main')
{
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    if (!file_exists($viewFile)) {
        http_response_code(404);
        echo 'View not found';
        return;
    }
    extract($data);
    if ($layout) {
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        $layoutFile = __DIR__ . '/../views/' . $layout . '.php';
        include $layoutFile;
    } else {
        include $viewFile;
    }
}

function redirect(string $path)
{
    header('Location: ' . $path);
    exit;
}

function flash(string $key, $value = null)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if ($value === null) {
        if (isset($_SESSION['flash'][$key])) {
            $val = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $val;
        }
        return null;
    }
    $_SESSION['flash'][$key] = $value;
}

function require_admin_auth()
{
    if (empty($_SESSION['admin_user'])) {
        redirect('/admin/login');
    }
}

function get_display_currency(): string
{
    if (!isset($_SESSION)) {
        session_start();
    }
    $config = config_value('currency', []);
    $default = $config['default_display'] ?? ($config['base'] ?? 'UGX');
    $rates = $config['rates'] ?? [];

    if (!empty($_GET['currency'])) {
        $choice = strtoupper(trim((string)$_GET['currency']));
        if (array_key_exists($choice, $rates)) {
            $_SESSION['display_currency'] = $choice;
        }
    }

    return $_SESSION['display_currency'] ?? $default;
}

function convert_amount($amount, string $from = 'UGX', string $to = null)
{
    $config = config_value('currency', []);
    $rates = $config['rates'] ?? [];
    $base = $config['base'] ?? 'UGX';
    $to = $to ?: get_display_currency();

    if ($from === $to) {
        return (float)$amount;
    }

    if (!isset($rates[$from]) || !isset($rates[$to]) || $rates[$from] == 0) {
        return (float)$amount;
    }

    // Convert to base first, then to target to avoid asymmetric rates.
    $amountInBase = ($from === $base) ? (float)$amount : ((float)$amount / (float)$rates[$from]);
    return ($to === $base) ? $amountInBase : $amountInBase * (float)$rates[$to];
}

function format_money($amount, $currency = 'UGX')
{
    $target = get_display_currency();
    $converted = convert_amount($amount, $currency, $target);
    return $target . ' ' . number_format((float)$converted, 2);
}
