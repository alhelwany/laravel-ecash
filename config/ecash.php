<?php

// config for Alhelwany/LaravelEcash
return [
    'gatewayUrl' => env('ECASH_GATEWAY_URL', 'https://checkout.ecash-pay.co'),
    'terminalKey' => env('ECASH_TERMINAL_KEY', null),
    'merchantId' => env('ECASH_MERCHANT_ID', null),
    'merchantSecret' => env('ECASH_MERCHANT_SECRET', null),
    'username' => env('ECASH_USERNAME', null),
    'password' => env('ECASH_PASSWORD', null),
];
