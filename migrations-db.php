<?php

use Core\Modules\Data\Env;

require 'vendor/autoload.php';

Env::init();

return [
    'dbname' => Env::get('POSTGRES_DB'),
    'user' => Env::get('POSTGRES_USER'),
    'password' => Env::get('POSTGRES_PASSWORD'),
    'host' => Env::get('POSTGRES_HOST'),
    'port' => Env::get('POSTGRES_PORT'),
    'driver' => 'pdo_pgsql'
];
