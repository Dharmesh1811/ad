<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Razorpay Configuration
    |--------------------------------------------------------------------------
    |
    | This file holds the configuration for Razorpay payment gateway.
    | Keys are read from the .env file for security — never hardcode them here.
    |
    | Get your API keys from: https://dashboard.razorpay.com/app/keys
    |
    */

    /**
     * Razorpay Key ID
     * Format: rzp_test_xxxxxxxx (test) or rzp_live_xxxxxxxx (production)
     */
    'key_id' => env('RAZORPAY_KEY_ID', ''),

    /**
     * Razorpay Key Secret
     * Keep this strictly server-side — never expose in frontend JS.
     */
    'key_secret' => env('RAZORPAY_KEY_SECRET', ''),

    /**
     * Currency for all transactions (ISO 4217 code)
     * Default: INR (Indian Rupee)
     */
    'currency' => env('RAZORPAY_CURRENCY', 'INR'),

];
