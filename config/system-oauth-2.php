<?php

return [
    'lightspeed' => [
        'client_id' => env('LIGHTSPEED_OAUTH_CLIENT_ID'),
        'client_secret' => env('LIGHTSPEED_OAUTH_CLIENT_SECRET'),
        'redirect_url' => sprintf(env('LIGHTSPEED_OAUTH_REDIRECT_URL'), env('LIGHTSPEED_OAUTH_CLIENT_ID'))
    ],
    'shopify' => [
        'client_id' => env('SHOPIFY_OAUTH_API_KEY'),
        'client_secret' => env('SHOPIFY_OAUTH_API_SECRET'),
        'authorised_redirect_url' => env('SHOPIFY_OAUTH_AUTHORISED_REDIRECT_URL'),
        'redirect_url' => 'https://%s/admin/oauth/authorize?client_id=%s&scope=%s&redirect_uri=%s&grant_options[]=value',
        'access_token_url' => 'https://%s/admin/oauth/access_token',
        'scopes' => 'read_orders,read_customers,read_customer_payment_methods,read_discounts,read_fulfillments,write_fulfillments,read_gift_cards,read_inventory,write_inventory,read_locations,read_products,read_shipping',
    ],
    'netsuite' => [
        'client_id' => env('NETSUITE_OAUTH_CLIENT_ID'),
        'client_secret' => env('NETSUITE_OAUTH_CLIENT_SECRET'),
        'redirect_url' => env('NETSUITE_OAUTH_REDIRECT_URL'),
        'callback_url' => env('NETSUITE_OAUTH_CALLBACK_URL'),
        'access_token_url' => sprintf(env('NETSUITE_OAUTH_ACCESS_TOKEN_URL'), env('NETSUITE_OAUTH_ACCOUNT_ID')),
        'scope' => env('NETSUITE_OAUTH_SCOPE'),
    ]
];
