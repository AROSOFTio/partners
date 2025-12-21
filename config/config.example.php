<?php
return [
    'app_name' => 'BenTech Collaborations',
    'base_url' => 'https://partners.bentechs.com',
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
        'from_email' => 'noreply@bentechs.com',
        'from_name' => 'BenTech Collaborations',
        'admin_to' => 'bangella23@gmail.com',
        'encryption' => 'tls',
    ],
    'pesapal' => [
        'consumer_key' => 'tHcfGgFoSjsYA9oHK3i/GF8T1fJz9QU0',
        'consumer_secret' => 'bAcwvIiIUKQdRTwPr3SbdkQHzAY=',
        'callback_url' => 'https://partners.bentechs.com/payment/callback',
        'ipn_url' => 'https://partners.ug/api/pesapal_ipn.php',
        'description' => 'BenTech Collaboration Payment',
        'demo' => false,
        'api_base' => 'https://pay.pesapal.com/v3/api',
        // Optional: pre-created IPN id; if empty we will attempt to register automatically.
        'ipn_id' => '',
    ],
    'currency' => [
        'base' => 'UGX',
        'default_display' => 'USD',
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
