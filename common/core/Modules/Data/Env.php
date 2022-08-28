<?php

declare(strict_types=1);

namespace Core\Modules\Data;

use Core\Modules\Data\Exceptions\EnvException;
use Core\Modules\Filesystem\Storage;

class Env
{
    private static array $values = [];

    private string $env = ROOT . '/.env';
    private string $envExample = ROOT . '/.env.example';

    /**
     * @throws EnvException
     */
    public function update(): void
    {
        self::$values = [];

        $this->loadFromServer();
        $this->loadFromFiles();
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
        $content = Storage::exists($this->env) ? Storage::get($this->env, true) : $this->generateFromExample();
        if (empty($content)) {
            EnvException::envFilesNotFound();
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

    private function generateFromExample(): ?array
    {
        if (Storage::exists($this->envExample)) {
            Storage::save($this->env, Storage::get($this->envExample));

            return Storage::get($this->env, true);
        }

        return null;
    }
}
