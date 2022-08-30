<?php

declare(strict_types=1);

namespace Core\Exceptions\Interfaces;

use Core\Components\Http\Enums\ResponseCode;

interface CoreExceptionInterface
{
    public function __construct(string $message = null, int|ResponseCode $responseCode = 500);
}
