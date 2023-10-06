<?php

return [
    'user_reset_password_url' => env('USER_RESET_PASSWORD_URL'),

    /*
    |--------------------------------------------------------------------------
    | Integration Server Name
    |--------------------------------------------------------------------------
    |
    | This value is the server name assigned to new integrations when created
    | and is dependant on the current environment.
    | e.g. 'tapestry-stage-k8s'
    |
    */

    'integration_server' => env('DEFAULT_TAPESTRY_SERVER_NAME', 'tapestry-local-k8s'),
];
