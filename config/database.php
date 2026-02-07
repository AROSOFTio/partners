<?php
if (!isset($GLOBALS['config']) || !is_array($GLOBALS['config'])) {
    $configPath = file_exists(__DIR__ . '/config.php')
        ? __DIR__ . '/config.php'
        : __DIR__ . '/config.example.php';
    if (file_exists($configPath)) {
        $GLOBALS['config'] = require $configPath;
    } else {
        $GLOBALS['config'] = [];
    }
}

function getPDO(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $db = $GLOBALS['config']['db'] ?? [];
    $host = $db['host'] ?? '127.0.0.1';
    $port = $db['port'] ?? 3306;
    $name = $db['name'] ?? 'partners';
    $user = $db['user'] ?? 'root';
    $pass = $db['pass'] ?? '';
    $charset = $db['charset'] ?? 'utf8mb4';

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    return $pdo;
}
