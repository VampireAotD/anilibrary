<?php

declare(strict_types=1);

return [
    'auth' => [
        'username' => env('ELASTICSEARCH_USER', ''),
        'password' => env('ELASTICSEARCH_PASSWORD', ''),
    ],

    'hosts' => [
        [
            'host'   => env('ELASTICSEARCH_HOST', ''),
            'port'   => env('ELASTICSEARCH_PORT', 9200),
            'scheme' => env('ELASTICSEARCH_SCHEME', 'http'),
        ],
    ],
];
