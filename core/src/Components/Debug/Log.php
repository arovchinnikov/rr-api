<?php

declare(strict_types=1);

namespace Core\Components\Debug;

use Carbon\Carbon;
use Core\Components\Data\Env;
use Core\Components\Debug\Enums\LogLevel;
use Core\Components\Debug\Interfaces\Logger;
use Core\Components\Filesystem\Storage;
use ErrorException;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class Log implements Logger
{
    private static string $defaultStoragePath = '/logs';

    public static function emergency(mixed $message, array $context = []): void
    {
        self::log(LogLevel::emergency, $message, $context);
    }

    public static function alert(mixed $message, array $context = []): void
    {
        self::log(LogLevel::alert, $message, $context);
    }

    public static function critical(mixed $message, array $context = []): void
    {
        self::log(LogLevel::critical, $message, $context);
    }

    public static function error(mixed $message, array $context = []): void
    {
        self::log(LogLevel::error, $message, $context);
    }

    public static function warning(mixed $message, array $context = []): void
    {
        self::log(LogLevel::warning, $message, $context);
    }

    public static function notice(mixed $message, array $context = []): void
    {
        self::log(LogLevel::notice, $message, $context);
    }

    public static function info(mixed $message, array $context = []): void
    {
        self::log(LogLevel::info, $message, $context);
    }

    public static function debug(mixed $message, array $context = []): void
    {
        self::log(LogLevel::debug, $message, $context);
    }

    public static function log(LogLevel $level, mixed $message, array $context = []): void
    {
        $storage = ROOT . (Env::get('LOG_STORAGE') ?? self::$defaultStoragePath);
        $logPath = $storage . '/';

        $logPath .= 'main.log';

        try {
            $cloner = new VarCloner();
            $dumper = new CliDumper();
            $message =  $dumper->dump($cloner->cloneVar($message), true);
        } catch (ErrorException $exception) {
            $message = (string)$exception;
        }

        $log = self::prepareLog(
            $level,
            $message,
            $context
        );

        Storage::save($logPath, implode(PHP_EOL, $log), FILE_APPEND);
    }

    private static function prepareLog(LogLevel $level, string $message, array $context): array
    {
        return [
            '[' . Carbon::now()->format('Y-m-d H:i:s') . '] Log level: ' . $level->name,
            $message,
            implode(',', $context)
        ];
    }
}
