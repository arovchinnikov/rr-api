<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Core\Components\Http\Enums\ResponseCode;
use Core\Exceptions\Interfaces\AppExceptionInterface;
use Exception;

class AppException extends Exception implements AppExceptionInterface
{
    protected int $errorCode;

    public function __construct(string $message = null, int $errorCode = 0, int|ResponseCode $responseCode = 500)
    {
        if ($responseCode instanceof ResponseCode) {
            $responseCode = $responseCode->value;
        }

        $this->errorCode = $errorCode;
        parent::__construct($message ?? '', $responseCode);
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }
}
