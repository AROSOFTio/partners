<?php
return [
    'app_name' => 'BenTech Collaborations',
    // Update this to your local vhost (Laragon default: http://partners.test)
    'base_url' => 'https://partners.bentechs.com',
    'security' => [
        'session_name' => 'bentech_session',
    ],
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'partners',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'currency' => [
        'base' => 'UGX',
        'default_display' => 'USD',
        // Rates are relative to base currency (1 UGX = X target)
        'rates' => [
            'UGX' => 1,
            'USD' => 0.00026,
            'EUR' => 0.00024,
        ],
    ],
    'contact' => [
        // Include country code, numbers only (example: 2567XXXXXXXX)
        'whatsapp_number' => '',
    ],
    'mail' => [
        'from_email' => 'no-reply@example.com',
        'from_name' => 'BenTech Collaborations',
        'admin_to' => 'admin@example.com',
    ],
    'pesapal' => [
        'demo' => true,
        'consumer_key' => '',
        'consumer_secret' => '',
        'callback_url' => '',
        'iframe_url' => '',
        'verify_url' => '',
    ],
];
