<?php

declare(strict_types=1);

use Core\Modules\Data\Config;
use Core\Modules\Data\Env;

function env(string $key = null): bool|array|string|null
{
    return Env::get($key);
}

function config(string $key = null): mixed
{
    return Config::get($key);
}
