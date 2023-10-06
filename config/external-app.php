<?php

return [
    'authentication_endpoints'  => [
        'bigcommerce'           => 'v2/orders',
        'brightpearl'           => 'warehouse-service/warehouse',
        'dynamics_nav'          => 'Services?wsdl',
        'linnworks'             => 'api/Auth/AuthorizeByApplication',
        'lightspeed'            => 'https://cloud.lightspeedapp.com/oauth/access_token.php',
        'magentotwo'            => 'index.php/rest/V1/store/websites',
        'mirakl'                => 'account',
        'netsuite_datacenter'   => 'https://rest.netsuite.com/rest/datacenterurls',
        'netsuite_restlet'      => 'app/site/hosting/restlet.nl',
        'ometria'               => 'v2/contacts',
        'peoplevox'             => 'resources/IntegrationServicev4.asmx?wsdl',
        'rebound'               => 'api/orders/search',
        'shopify'               => 'admin/oauth/access_scopes.json',
        'vend'                  => 'api/2.0/registers',
        'visualsoft'            => 'api/soap/service',
        'torque'                => 'status'
    ],
    'webhook_endpoints' => [
        'shopify' => 'admin/api/2022-04/webhooks'
    ]
];
