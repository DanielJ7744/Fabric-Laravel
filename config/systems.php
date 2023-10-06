<?php

return [
    'netsuite' => [
        'consumer_key' => env('NETSUITE_CONSUMER_KEY'),
        'consumer_secret' => env('NETSUITE_CONSUMER_SECRET')
    ],
    'linnworks' => [
        'applicationid' => env('LINNWORKS_APPLICATION_ID'),
        'applicationsecret' => env('LINNWORKS_APPLICATION_SECRET')
    ],
    's3' => [
        'key' => env('S3_AWS_KEY'),
        'secret' => env('S3_AWS_SECRET')
    ],
    'bi' => [
        'key' => env('BI_AWS_KEY'),
        'secret' => env('BI_AWS_SECRET')
    ]
];
