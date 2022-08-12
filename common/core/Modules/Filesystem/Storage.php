<?php

declare(strict_types=1);

namespace Core\Modules\Filesystem;

use Core\Base\Interfaces\Types\ToArray;
use Core\Base\Interfaces\Types\ToString;

class Storage
{
    public static function save($path, ToString|ToArray|string|array $content, int $flag = 0): bool
    {
        return (bool)file_put_contents($path, $content, $flag);
    }

    public static function get($path): ?string
    {
        return file_get_contents($path) ?? null;
    }

    public static function exists($path): bool
    {
        return file_exists($path);
    }

    public static function scanDir(string $directory, bool $recurrent = false, bool $lineArray = false): ?array
    {
        if (!self::exists($directory)) {
            return null;
        }

        $dir = array_diff(scandir($directory), array('..', '.'));

        $scanData = [];
        foreach ($dir as $key => $file) {
            $scanData[$key] = $directory . '/' . $file;
        }

        if (!$recurrent) {
            return array_values($scanData);
        }

        $fullScanData = [];

        foreach ($dir as $key => $file) {
            if (!is_dir($directory . '/' . $file)) {
                $fullScanData[$key] = $directory . '/' . $file;
                continue;
            }

            if (!$lineArray) {
                $fullScanData[$key] = self::scanDir($directory . '/' . $file, true);
                continue;
            }

            $fullScanData = array_merge(
                $fullScanData,
                self::scanDir($directory . '/' . $file, true, true)
            );
        }

        return array_values($fullScanData);
    }
}
