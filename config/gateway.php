<?php

/**
 * Config for various external microservices / APIs for which Fabric acts as a gateway
 *
 * Top-level key should match the name of the Route Group which handles requests to this gateway
 * eg `transform_scripts` matches the `transformScripts` route group
 */

$logByDefault = env('GATEWAY_LOG_DEFAULT', true);

return [
    'transform_scripts' => [
        'api_url' => env('GATEWAY_TRANSFORM_SCRIPTS_URL', 'https://lib-script-dev.pwks.co'),
        'create_logs' => env('GATEWAY_TRANSFORM_SCRIPTS_CREATE_LOGS', $logByDefault)
    ]
];
