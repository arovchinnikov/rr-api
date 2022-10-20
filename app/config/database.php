<?php

use Core\Components\Database\Config\PostgresConnectionConfig;

return [
    'connections' => [
        'main' => new PostgresConnectionConfig(
            host:       env('MAIN_HOST'),
            port:  (int)env('MAIN_PORT'),
            dbName:     env('MAIN_DBNAME'),
            user:       env('MAIN_USER'),
            password:   env('MAIN_PASSWORD')
        )
    ]
];
