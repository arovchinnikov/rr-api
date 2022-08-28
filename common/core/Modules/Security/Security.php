<?php

declare(strict_types=1);

namespace Core\Modules\Security;

class Security
{
    private static HashManager $hashManager;

    private string $appSecret = '';

    public static function hashPassword(string $password): string
    {
        return self::$hashManager->prepare($password);
    }

    public function init(): void
    {
        $this->setDefaultHashManager();
    }

    public function setAppSecret(string $appSecret = null): void
    {
        $this->appSecret = $appSecret ?? '';
    }

    public function setDefaultHashManager(): void
    {
        $manager = new HashManager();
        $manager->setAlg('sha512');
        $manager->setSalt($this->appSecret);

        self::$hashManager = $manager;
    }
}
