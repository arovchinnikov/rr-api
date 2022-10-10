<?php

declare(strict_types=1);

namespace Core\Components\Data\Exceptions;

use Core\Exceptions\CoreException;

class EnvException extends CoreException
{
    /**
     * @throws EnvException
     */
    public static function envFileNotFound(): void
    {
        throw new self('Error to find .env file');
    }
}
