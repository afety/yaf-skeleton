<?php

$config = new Yaf\Config\Ini(APPLICATION_CONFIGURATION_PATH, getYafEnviron());

return [
    'default' => 'default',

    'connections' => [

        'mysql' => [
            'default' => [
                'driver' => 'mysql',
                'host' => $config->get('mysql.params.host'),
                'port' => $config->get('mysql.params.port') ?? 3306,
                'database' => $config->get('mysql.params.database'),
                'username' => $config->get('mysql.params.username'),
                'password' => $config->get('mysql.params.password'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ],

        'mongodb' => [
            'mongodb' => [
                'driver' => 'mongodb',
                'host' => $config->get('mongodb.params.host'),
                'port' => $config->get('mongodb.params.port') ?? 27037,
                'database' => $config->get('mongodb.params.database'),
                'username' => $config->get('mongodb.params.username'),
                'password' => $config->get('mongodb.params.password'),
            ]
        ],
    ],
];
