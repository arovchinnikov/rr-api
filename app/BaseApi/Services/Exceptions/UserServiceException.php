<?php

declare(strict_types=1);

namespace App\BaseApi\Services\Exceptions;

use Core\Base\Exceptions\AppException;
use Core\Modules\Http\Enums\ResponseCode;

class UserServiceException extends AppException
{
    /**
     * @throws UserServiceException
     */
    public static function userWithLoginExists(): void
    {
        throw new self('User with passed login already exists', 1002, ResponseCode::conflict);
    }
}
