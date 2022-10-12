<?php

use Core\Components\Config\Env;

require 'vendor/autoload.php';
require 'core/bootstrap.php';

const ROOT = __DIR__;

$env = new Env();
$env->update();

return [
    'dbname' => env('POSTGRES_DB'),
    'user' => env('POSTGRES_USER'),
    'password' => env('POSTGRES_PASSWORD'),
    'host' => env('POSTGRES_HOST'),
    'port' => env('POSTGRES_PORT'),
    'driver' => 'pdo_pgsql'
];
