<?php

namespace Core\Components\Debug\Interfaces;

use Core\Components\Debug\Enums\LogLevel;

interface Logger
{
    public static function emergency(mixed $message, array $context = []): void;

    public static function alert(mixed $message, array $context = []): void;

    public static function critical(mixed $message, array $context = []): void;

    public static function error(mixed $message, array $context = []): void;

    public static function warning(mixed $message, array $context = []): void;

    public static function notice(mixed $message, array $context = []): void;

    public static function info(mixed $message, array $context = []): void;

    public static function debug(mixed $message, array $context = []): void;

    public static function log(LogLevel $level, mixed $message, array $context = []): void;
}