<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use Core\Base\DataValues\StringValue;
use Core\Exceptions\ValidationException;

class Email extends StringValue
{
    private ?string $email;

    public function __construct(string $email = null)
    {
        $this->email = $email;
    }

    public function validate(): void
    {
        $emailLen = strlen($this->email);

        if ($emailLen < 4 || $emailLen > 128) {
            throw new ValidationException('sss');
        }
    }

    public function getValue(): ?string
    {
        return $this->email;
    }
}
