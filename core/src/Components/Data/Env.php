<?php

declare(strict_types=1);

namespace Core\Components\Data;

use Core\Components\Data\Exceptions\EnvException;
use Core\Components\Filesystem\Storage;

class Env
{
    private static array $values = [];

    private string $env = ROOT . '/.env';

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
        $content = Storage::get($this->env, true);
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
