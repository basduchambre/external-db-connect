<?php

return [

    /*
    |--------------------------------------------------------------------------
    | External DB Connect - Database settings
    |--------------------------------------------------------------------------
    |
    | Make sure you're authenticated to connect to the external database
    | and set the IP, portal, username and password.
    |
    */

    'external_db' => [
        'driver' => 'mysql',
        'url' => env('DB_EXTERNAL_URL'),
        'host' => env('DB_EXTERNAL_HOST', '127.0.0.1'),
        'port' => env('DB_EXTERNAL_PORT', '3306'),
        'database' => env('DB_EXTERNAL_DATABASE', 'forge'),
        'table' => env('DB_EXTERNAL_TABLE', ''),
        'username' => env('DB_EXTERNAL_USERNAME', 'forge'),
        'password' => env('DB_EXTERNAL_PASSWORD', ''),
        'unix_socket' => env('DB_EXTERNAL_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

    /*
    |--------------------------------------------------------------------------
    | External DB Connect - Settings for fetching data
    |--------------------------------------------------------------------------
    |
    | Make sure you're authenticated to connect to the external database
    | and set the IP, portal, username and password.
    |
    */

    'migration' => [
        'columns' => [
            [
                'name' => 'email_address',
                'type' => 'string',
                'default' => '',
                'nullable' => true,
            ],
            [
                'name' => 'name',
                'type' => 'string',
                'default' => '',
                'nullable' => true,
            ],
        ],
    ],

];
