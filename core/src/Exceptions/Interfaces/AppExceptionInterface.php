<?php

declare(strict_types=1);

namespace Core\Exceptions\Interfaces;

use Core\Components\Http\Enums\ResponseCode;

interface AppExceptionInterface
{
    public function __construct(string $message = null, int $errorCode = 0, int|ResponseCode $responseCode = 0);

    public function getErrorCode(): int;
}