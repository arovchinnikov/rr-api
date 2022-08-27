<?php

declare(strict_types=1);

namespace App\BaseApi\DataObjects\User\Exceptions;

use Core\Base\Exceptions\AppException;
use Core\Base\Exceptions\ValidationException;

class PasswordException extends ValidationException
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
