<?php

declare(strict_types=1);

namespace Core\Components\Data\Exceptions;

use Core\Base\Exceptions\CoreException;

class EnvException extends CoreException
{
    /**
     * @throws EnvException
     */
    public static function envFilesNotFound(): void
    {
        throw new self('Error to find .env or .env.example file');
    }
}
