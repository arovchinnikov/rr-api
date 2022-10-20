<?php

declare(strict_types=1);

namespace Core\Components\Config;

use Core\Base\Interfaces\BootloaderInterface;
use Core\Components\Filesystem\Storage;

class ConfigBootloader implements BootloaderInterface
{
    private string $configPath = ROOT . '/app/config';

    public function run(): void
    {
        $configDir = Storage::scanDir($this->configPath, true, true);

        foreach ($configDir as $configFile) {
            $namespace = $this->namespace($this->configPath, $configFile);

            foreach (include $configFile as $key => $value) {
                Config::set($namespace . $key, $value);
            }
        }
    }

    private function namespace(string $storage, string $path): string
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
