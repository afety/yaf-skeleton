<?php


$config = new Yaf\Config\Ini(APPLICATION_CONFIGURATION_PATH, getYafEnviron());

return [
    'default' => 'default',

    'connections' => [
        'default' => [
            'driver' => 'redis',
            'host' => $config->get('redis.params.host') ?? '127.0.0.1',
            'port' => $config->get('redis.params.port') ?? 6379,
            'password' => $config->get('redis.params.password') ?? '',
            'database' => 0,
            'prefix' => $config->get('redis.params.prefix') ?? APP_NAME . ":",
        ],

        'test' => [
            'driver' => 'redis',
            'host' => $config->get('redis.params.host') ?? '127.0.0.1',
            'port' => $config->get('redis.params.port') ?? 6379,
            'password' => $config->get('redis.params.password') ?? '',
            'database' => 15,
            'prefix' => $config->get('redis.params.prefix') ?? APP_NAME . ":",
        ],
    ],
];
