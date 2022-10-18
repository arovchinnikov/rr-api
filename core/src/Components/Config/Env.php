<?php

declare(strict_types=1);

namespace Core\Components\Config;

use Core\Components\Config\Exceptions\EnvException;
use Core\Components\Filesystem\Storage;

class Env
{
    private static array $values = [];

    public static function get(string $key = null): mixed
    {
        return self::$values[$key];
    }

    public static function set(string $key, mixed $value): void
    {
        self::$values[$key] = $value;
    }
}
