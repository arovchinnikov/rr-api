<?php

declare(strict_types=1);

namespace Core\Components\Config;

use Core\Base\Interfaces\BootloaderInterface;
use Core\Components\Config\Exceptions\EnvException;
use Core\Components\Filesystem\Storage;

class EnvBootloader implements BootloaderInterface
{
    private string $envFile = ROOT . '/.env';

    /**
     * @throws EnvException
     */
    public function run(): void
    {
        foreach ($_ENV as $key => $value) {
            Env::set($key, $value);
        }

        $this->makeEnv();
    }

    /**
     * @throws EnvException
     */
    private function makeEnv(): void
    {
        $content = Storage::get($this->envFile, true);
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

            Env::set($value[0], $value[1]);
        }
    }
}
