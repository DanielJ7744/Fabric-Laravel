<?php

/*
|--------------------------------------------------------------------------
| Tapestry Config
|--------------------------------------------------------------------------
|
| Settings in this file determine whether a local or remote instance of Tapestry is used
| As well as whether calls to Tapestry servers should be forced to the local instance
|
*/

$useLocalTapestry = env('TAPESTRY_USE_LOCAL', false);

return [

    'use_local' => $useLocalTapestry,
    'local_url' => env('TAPESTRY_LOCAL_URL', 'tapestry'),
    'routes' => [
        'minidash' => 'api/minidash',
        'core' => 'api'
    ],
    'protocol' => env('TAPESTRY_PROTOCOL', 'https'),

    /*
     * When local tapestry is used
     * Whether the server URLs should be overridden
     * So that local tapestry is used for all integrations
     *
     * If this setting is false, the 'server' in each integration
     * Must be changed to 'tapestry' to ensure the local instance is used
     */
    'override_server_urls' => $useLocalTapestry && env('TAPESTRY_OVERRIDE_SERVER_URLS', true)

];
