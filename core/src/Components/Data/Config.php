<?php

declare(strict_types=1);

namespace Core\Components\Data;

use Core\Components\Filesystem\Storage;

class Config
{
    private static array $config = [];

    private string $storage = ROOT . '/app/common/config';

    public function update(): void
    {
        self::$config = [];

        $configDir = Storage::scanDir($this->storage, true, true);

        foreach ($configDir as $configFile) {
            $values = include $configFile;

            $namespace = $this->makeNamespace($this->storage, $configFile);
            $this->setConfigValues($namespace, $values);
        }
    }

    public static function get(string $key = null): mixed
    {
        return isset($key) ? self::$config[$key] : self::$config ?? null;
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
