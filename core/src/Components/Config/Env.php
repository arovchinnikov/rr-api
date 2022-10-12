<?php

declare(strict_types=1);

namespace Core\Components\Config;

use Core\Components\Config\Exceptions\EnvException;
use Core\Components\Filesystem\Storage;

class Env
{
    private static array $values = [];

    private static string $path;

    public static function setFile(string $path): void
    {
        self::$path = $path;
    }

    /**
     * @throws EnvException
     */
    public static function update(): void
    {
        self::$values = [];

        $self = new self();

        $self->loadFromServer();
        $self->loadFromFiles();
    }

    public static function get(string $key = null): null|bool|array|string
    {
        return self::$values[$key];
    }

    private function loadFromServer(): void
    {
        foreach ($_ENV as $key => $value) {
            self::$values[$key] = $value;
        }
    }

    /**
     * @throws EnvException
     */
    private function loadFromFiles(): void
    {
        $content = Storage::get(self::$path, true);
        if (empty($content)) {
            EnvException::envFileNotFound();
        }

        foreach ($content as $line) {
            if (str_starts_with($line, '#') || $line === '') {
                continue;
            }

            $value = explode('=', $line);
            if (count($value) !== 2) {
                continue;
            }

            self::$values[$value[0]] = $value[1];
        }
    }
}
