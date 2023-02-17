<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe API
    |--------------------------------------------------------------------------
    |
    |
    */

    'api' => [
        'pub_key' => env('STRIPE_KEY'),
        'sec_key' => env('STRIPE_SECRET'),
        'prod' => [
            'pub_key' => env('STRIPE_PROD_KEY'),
            'sec_key' => env('STRIPE_PROD_SECRET'),
        ]
    ],
    'webhook' => [
        'sec_key' => env('STRIPE_WEBHOOK_SECRET'),
        'prod' => [
            'sec_key' => env('STRIPE_PROD_WEBHOOK_SECRET')
        ]
    ]

];
