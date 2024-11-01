<?php

return [

    /*
    |--------------------------------------------------------------------------
    | M-Pesa API Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may specify the credentials needed to authenticate with the
    | M-Pesa API. These values should be provided by your M-Pesa account.
    |
    */

    'consumer_key' => $key = env('MPESA_CONSUMER_KEY', ''), 
    'consumer_secret' => $secret = env('MPESA_CONSUMER_SECRET', ''), 
    'shortcode' => env('MPESA_SHORTCODE', ''), // Your M-Pesa Shortcode
    'lipa_na_mpesa_shortcode' => env('MPESA_LNM_SHORTCODE', ''), // Lipa na M-Pesa Shortcode
    'lipa_na_mpesa_key' => env('MPESA_LNM_KEY', ''), // Lipa na M-Pesa Shortcode

    /*
    |--------------------------------------------------------------------------
    | M-Pesa Environment
    |--------------------------------------------------------------------------
    |
    | This option controls the environment that the M-Pesa API is
    | connecting to. You may choose between 'sandbox' for testing
    | or 'live' for production usage.
    |
    */

    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'), // 'sandbox' or 'live'

    /*
    |--------------------------------------------------------------------------
    | M-Pesa API URLs
    |--------------------------------------------------------------------------
    |
    | The URLs for M-Pesa's API endpoints. These are determined by the
    | environment you are using (sandbox or live).
    |
    */

    'api_urls' => [
        'sandbox' => [
            'base' => 'https://sandbox.safaricom.co.ke/',
            'register_url' => 'https://sandbox.safaricom.co.ke/mpesa/registerurl/v1',
            'token_url' => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
            'stk_push_url' => 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
            'query_stk_push_url' => 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query',
            // Add other API endpoints as necessary
        ],
        'live' => [
            'base' => 'https://api.safaricom.co.ke/',
            'register_url' => 'https://api.safaricom.co.ke/mpesa/registerurl/v1',
            'stk_push_url' => 'https://api.safaricom.co.ke/mpesa/stkpush/v1',
            // Add other API endpoints as necessary
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | M-Pesa Callbacks
    |--------------------------------------------------------------------------
    |
    | Here you may specify the callback URLs for the M-Pesa API.
    | These URLs will be called by M-Pesa after a transaction is processed.
    |
    */

    'callbacks' => [
        'result_url' => env('MPESA_RESULT_URL', 'https://d4e5-197-248-78-185.ngrok-free.app/api/mpesa/callback'), // URL for transaction results
        'validation_url' => env('MPESA_VALIDATION_URL', 'http://yourapp.com/mpesa/validation'), // URL for validation
    ],

];
