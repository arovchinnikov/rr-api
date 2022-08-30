<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects\Exceptions;

class PasswordException extends \Core\Exceptions\ValidationException
{
    /**
     * @throws PasswordException
     */
    public static function invalidPasswordLen(int $len): void
    {
        throw new self(
            'Password must contain no less than 5 and no more than 64 characters. Current password size - ' . $len . ' characters.',
            3002,
            400
        );
    }
}
