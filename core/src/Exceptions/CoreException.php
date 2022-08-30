<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Core\Components\Http\Enums\ResponseCode;
use Core\Exceptions\Interfaces\CoreExceptionInterface;
use Exception;

class CoreException extends Exception implements CoreExceptionInterface
{
    public function __construct(string $message = null, int|ResponseCode $responseCode = 500)
    {
        if ($responseCode instanceof ResponseCode) {
            $responseCode = $responseCode->value;
        }

        parent::__construct($message ?? '', $responseCode);
    }
}
