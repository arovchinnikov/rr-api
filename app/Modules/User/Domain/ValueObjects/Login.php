<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use App\Modules\User\Domain\ValueObjects\Exceptions\LoginException;
use Core\Base\DataValues\StringValue;

class Login extends StringValue
{
    protected string $login;

    public function __construct(string $login)
    {
        $this->login = $login;
    }

    /**
     * @throws LoginException
     */
    public function validate(): void
    {
        $loginLen = strlen($this->login);

        if ($loginLen < 3 || $loginLen > 16) {
            LoginException::invalidLoginLen($loginLen);
        }
    }

    public function getValue(): string
    {
        return $this->login;
    }
}
