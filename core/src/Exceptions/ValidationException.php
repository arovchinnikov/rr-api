<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Core\Components\Http\Enums\ResponseCode;

class ValidationException extends AppException
{
    public function __construct(
        string $message = null,
        int $errorCode = 1000,
        ResponseCode|int $code = 400,
    ) {
        parent::__construct($message, $errorCode, $code);
    }
}
