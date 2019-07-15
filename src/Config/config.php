<?php

return [
    'adapter' => env('PROMETHEUS_ADAPTER', 'apc'),
    
    'namespace' => 'app',
    
    'namespace_http' => 'http',
    
    'redis' => [
        'host'                   => env('PROMETHEUS_REDIS_HOST', env('REDIS_HOST', '127.0.0.1')),
        'port'                   => env('PROMETHEUS_REDIS_PORT', env('REDIS_PORT', 6379)),
        'password'               => env('PROMETHEUS_REDIS_PASSWORD', env('REDIS_PASSWORD', null)),
        'timeout'                => 0.1,  // in seconds
        'read_timeout'           => 10, // in seconds
        'persistent_connections' => false,
    ],
    
    'push_gateway' => [
        'address' => env('PROMETHEUS_PUSH_GATEWAY_ADDRESS', 'localhost:9091')
    ],
    
    'buckets_per_route' => []
];
