<?php

declare(strict_types=1);

namespace App\BaseApi\DataObjects\User;

use App\BaseApi\DataObjects\User\Exceptions\PasswordException;
use Core\Base\DataValues\StringValue;
use Core\Modules\Security\PasswordManager;

class Password extends StringValue
{
    protected string $password;
    protected string $hash;

    public function __construct(string $password)
    {
        if (strlen($password) === 128) {
            $this->hash = $password;
            return;
        }

        $this->password = $password;
        $this->hash = PasswordManager::hash($password);
    }

    /**
     * @throws PasswordException
     */
    public function validate(): void
    {
        if (empty($this->password)) {
            return;
        }

        $passLen = strlen($this->password);

        if ($passLen < 5 || $passLen > 64) {
            PasswordException::invalidPasswordLen($passLen);
        }
    }

    public function getValue(): ?string
    {
        return $this->hash ?? null;
    }

    public function getRaw(): ?string
    {
        return $this->password ?? null;
    }
}
