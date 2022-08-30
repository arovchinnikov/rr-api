<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects\Exceptions;

class LoginException extends \Core\Exceptions\ValidationException
{
    /**
     * @throws LoginException
     */
    public static function invalidLoginLen(int $len): void
    {
        throw new self(
            'Login length must be 3-16 characters. Current login length - ' . $len . ' characters.',
            3001,
            400
        );
    }
}
