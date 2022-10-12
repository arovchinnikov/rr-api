<?php

declare(strict_types=1);

namespace Core\Components\Config;

use Core\Components\Filesystem\Storage;

class Config
{
    private static array $config = [];
    private static string $path;

    public static function setPath(string $storage): void
    {
        self::$path = $storage;
    }

    public static function update(): void
    {
        self::$config = [];

        $self = new self();

        $configDir = Storage::scanDir(self::$path, true, true);

        foreach ($configDir as $configFile) {
            $values = include $configFile;

            $namespace = $self->makeNamespace(self::$path, $configFile);
            $self->setConfigValues($namespace, $values);
        }
    }

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

    private function setConfigValues(string $namespace, array $config): void
    {
        foreach ($config as $key => $value) {
            self::$config[$namespace . $key] = $value;
        }
    }

    private function makeNamespace(string $storage, string $path): string
    {
        return str_replace(
            '/',
            '.',
            str_replace(
                $storage . '/',
                '',
                str_replace(
                    'php',
                    '',
                    $path
                )
            )
        );
    }
}
