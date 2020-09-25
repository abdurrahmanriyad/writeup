<?php

return [
    'run_in_production' => env('WRITEUP_RUN_IN_PRODUCTION', true),
    'request_log' => [
        'enable' => env('WRITEUP_REQUEST_LOG', true),
        'header' => [
            'enable' => true,
            /**
             *** Following are some default headers. You may pass names which you want to log
             * host
             * connection
             * cache-control
             * upgrade-insecure-requests
             * user-agent
             * accept
             * sec-fetch-site
             * sec-fetch-mode
             * sec-fetch-user
             * sec-fetch-dest
             * accept-encoding
             * accept-language
             * cookie
             * cache-control
             */
            'take' => ['host', 'connection', 'cache-control', 'user-agent', 'accept'],
        ],
        'url' => true,
        'method' => true,
        'params' => true
    ],
    'response_log' => [
        'enable' => env('WRITEUP_RESPONSE_LOG', true),
        'time' => true // Time required to execute a request
    ],
    'query_log' => [
        'enable' => env('WRITEUP_QUERY_LOG', true),
        'sql' => true,
        'execution_time' => true,
        'connection' => true,
        'request_url' => true,
        'request_method' => true,
    ]
];
