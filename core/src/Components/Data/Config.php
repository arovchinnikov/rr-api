<?php

declare(strict_types=1);

namespace Core\Components\Data;

use Core\Components\Filesystem\Storage;

class Config
{
    private static array $config = [];

    private static string $storage = ROOT . '/app/common/config';

    public static function update(): void
    {
        self::$config = [];

        $self = new self();

        $configDir = Storage::scanDir(self::$storage, true, true);

        foreach ($configDir as $configFile) {
            $values = include $configFile;

            $namespace = $self->makeNamespace(self::$storage, $configFile);
            $self->setConfigValues($namespace, $values);
        }
    }

    public static function get(string $key = null): mixed
    {
        $keyParts = explode('.', $key);

        if (count($keyParts) === 2) {
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
