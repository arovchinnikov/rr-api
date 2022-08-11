<?php

declare(strict_types=1);

namespace Core\Modules\Data;

use Core\Modules\Data\Enums\EnvKey;

class Env
{
    private static array $env = [];

    public static function init(): void
    {
        foreach (getenv() as $key => $value) {
            $enum = EnvKey::tryFrom($key);

            if (empty($enum)) {
                continue;
            }

            if ($enum->type() === 'bool') {
                $value = boolval($value);
            }

            if ($enum->type() === 'int') {
                $value = intval($value, 0);
            }

            self::$env[$key] = $value;
        }
    }

    public static function get(EnvKey|string $key = null): bool|array|string
    {
        if (empty($key)) {
            return self::$env;
        }

        if (is_string($key)) {
            return self::$env[$key];
        }

        return self::$env[$key->value];
    }
}
