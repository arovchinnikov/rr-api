<?php

declare(strict_types=1);

namespace Core\Base\Exceptions;

use Core\Modules\Http\Enums\ResponseCode;
use Throwable;

class ValidationException extends AppException
{
    public function __construct(
        string $message = "",
        int $errorCode = 1000,
        ResponseCode|int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $code, $previous);
    }
}
