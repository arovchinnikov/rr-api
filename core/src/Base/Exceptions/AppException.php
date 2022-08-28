<?php

declare(strict_types=1);

namespace Core\Base\Exceptions;

use Core\Components\Http\Enums\ResponseCode;
use Exception;
use Throwable;

class AppException extends Exception
{
    protected int $errorCode;

    public function __construct(
        string $message = "",
        int $errorCode = 0,
        int|ResponseCode $code = 0,
        ?Throwable $previous = null
    ) {
        if ($code instanceof ResponseCode) {
            $code = $code->value;
        }

        $this->errorCode = $errorCode;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }
}
