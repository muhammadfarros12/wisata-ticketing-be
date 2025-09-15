<?php


return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'YOUR_MIDTRANS_MERCHANT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'YOUR_MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'YOUR_MIDTRANS_SERVER_KEY'),

    'is_production' => false,
    'is_3ds' => false,
    'is_sanitized' => false
];
