<?php
// Front controller
$configPath = file_exists(__DIR__ . '/../config/config.php')
    ? __DIR__ . '/../config/config.php'
    : __DIR__ . '/../config/config.example.php';
$GLOBALS['config'] = require $configPath;
session_name($GLOBALS['config']['security']['session_name'] ?? 'bentech_session');
session_start();

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/Router.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../controllers/' . $class . '.php',
        __DIR__ . '/../models/' . $class . '.php',
        __DIR__ . '/../lib/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$router = new Router();

$router->add('GET', '/', [new HomeController(), 'index']);
$router->add('GET', '/packages', [new PackageController(), 'index']);
$router->add('GET', '/package', [new PackageController(), 'view']);
$router->add('GET', '/package/{slug}', [new PackageController(), 'view']);

$router->add('GET', '/request', [new OrderController(), 'request']);
$router->add('POST', '/request', [new OrderController(), 'request']);
$router->add('GET', '/checkout', [new OrderController(), 'checkout']);

$router->add('POST', '/payment/create', [new PaymentController(), 'create']);
$router->add('GET', '/payment/create', [new PaymentController(), 'create']);
$router->add('GET', '/payment/callback', [new PaymentController(), 'callback']);
$router->add('GET', '/payment/ipn', [new PaymentController(), 'ipn']);
$router->add('POST', '/payment/ipn', [new PaymentController(), 'ipn']);
$router->add('GET', '/api/pesapal_ipn.php', [new PaymentController(), 'ipn']);
$router->add('POST', '/api/pesapal_ipn.php', [new PaymentController(), 'ipn']);
$router->add('GET', '/payment/complete', [new PaymentController(), 'complete']);
$router->add('GET', '/payments/error', function () {
    $message = $_GET['message'] ?? null;
    view('payments/error', ['message' => $message]);
});

$router->add('GET', '/portfolio', [new PortfolioController(), 'index']);

$router->add('GET', '/admin/login', [new AdminController(), 'login']);
$router->add('POST', '/admin/login', [new AdminController(), 'login']);
$router->add('GET', '/admin/logout', [new AdminController(), 'logout']);
$router->add('GET', '/admin', [new AdminController(), 'dashboard']);
$router->add('GET', '/admin/packages', [new AdminController(), 'packages']);
$router->add('GET', '/admin/packages/edit', [new AdminController(), 'editPackage']);
$router->add('POST', '/admin/packages/edit', [new AdminController(), 'editPackage']);
$router->add('GET', '/admin/orders', [new AdminController(), 'orders']);
$router->add('GET', '/admin/orders/view', [new AdminController(), 'viewOrder']);
$router->add('GET', '/admin/portfolio', [new AdminController(), 'portfolio']);
$router->add('POST', '/admin/portfolio', [new AdminController(), 'portfolio']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
