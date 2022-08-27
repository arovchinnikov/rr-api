<?php

declare(strict_types=1);

namespace Core\Modules\Database\Exceptions;

use Core\Base\Exceptions\CoreException;
use PDOException;

class ConnectionException extends CoreException
{
    /**
     * @throws ConnectionException
     */
    public static function alreadyConnected(): self
    {
        throw new self();
    }

    /**
     * @throws ConnectionException
     */
    public static function connectionFailed(PDOException $e): self
    {
        throw new self();
    }
}
