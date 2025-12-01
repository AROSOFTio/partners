<?php
return [
    'app_name' => 'BenTech Collaborations',
    'base_url' => 'https://partners.ug',
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'partners',
        'user' => 'dbuser',
        'pass' => 'secret',
        'charset' => 'utf8mb4',
    ],
    'mail' => [
        'driver' => 'mail', // or smtp
        'host' => 'smtp.example.com',
        'port' => 587,
        'username' => 'user@example.com',
        'password' => 'password',
        'from_email' => 'no-reply@bentechs.io',
        'from_name' => 'BenTech Collaborations',
        'encryption' => 'tls',
    ],
    'pesapal' => [
        'consumer_key' => 'PESAPAL_CONSUMER_KEY',
        'consumer_secret' => 'PESAPAL_CONSUMER_SECRET',
        'callback_url' => 'https://partners.bentechs.io/payment/callback',
        'ipn_url' => 'https://partners.bentechs.io/payment/callback', // or a dedicated IPN endpoint
        'description' => 'BenTech Collaboration Payment',
        'demo' => true,
        'api_base' => 'https://pay.pesapal.com/v3/api',
        // Optional: set if you already registered an IPN URL in Pesapal backoffice
        'ipn_id' => '',
    ],
    'currency' => [
        'base' => 'UGX',
        'default_display' => 'USD',
        // Simple static rates relative to the base currency. Update with live rates in production.
        'rates' => [
            'UGX' => 1,
            'USD' => 0.00027,
            'EUR' => 0.00025,
        ],
    ],
    'security' => [
        'session_name' => 'bentech_session',
    ],
];
