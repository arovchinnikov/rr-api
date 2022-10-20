<?php

declare(strict_types=1);

use Core\Components\Config\Config;
use Core\Components\Config\Env;

function env(string $key, mixed $default = null): mixed
{
    return Env::get($key) ?? $default;
}

function config(string $key, mixed $default = null): mixed
{
    return Config::get($key) ?? $default;
}
