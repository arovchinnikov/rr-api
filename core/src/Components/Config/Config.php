<?php

declare(strict_types=1);

namespace Core\Components\Config;

class Config
{
    private static array $config = [];

    public static function get(string $key = null): mixed
    {
        $keyParts = explode('.', $key);

        if (count($keyParts) < 3) {
            return isset($key) ? self::$config[$key] : self::$config ?? null;
        }

        $keyParts[0] = $keyParts[0] . '.' . $keyParts[1];
        unset($keyParts[1]);

        $value = self::$config;
        foreach ($keyParts as $part) {
            $value = $value[$part] ?? null;
        }

        return $value;
    }

    public static function set(string $key, mixed $value): void
    {
        self::$config[$key] = $value;
    }
}
