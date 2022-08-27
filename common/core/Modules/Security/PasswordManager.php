<?php

declare(strict_types=1);

namespace Core\Modules\Security;

use Core\Modules\Data\Env;

class PasswordManager
{
    private static string $appSecret;

    public function __construct()
    {
        self::$appSecret = Env::get('APP_SECRET') ?? '';
    }

    public static function hash(string $password): string
    {
        return self::getHash($password);
    }

    public static function compare(string $password, string $hash): bool
    {
        $hashedPassword = self::getHash($password);

        return $hashedPassword === $hash;
    }

    public static function getHash(string $string): string
    {
        return hash('sha512', $string.self::$appSecret);
    }
}
