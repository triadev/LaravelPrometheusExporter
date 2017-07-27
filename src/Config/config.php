<?php

return [
    'adapter' => env('PROMETHEUS_ADAPTER', 'apc'),

    'namespace' => 'app',

    'namespace_http_server' => 'http_server',

    'redis' => [
        'host'                   => env('PROMETHEUS_REDIS_HOST', '127.0.0.1'),
        'port'                   => env('PROMETHEUS_REDIS_PORT', 6379),
        'timeout'                => 0.1,  // in seconds
        'read_timeout'           => 10, // in seconds
        'persistent_connections' => false,
    ],
];

