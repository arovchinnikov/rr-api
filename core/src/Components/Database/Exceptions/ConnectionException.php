<?php

declare(strict_types=1);

namespace Core\Components\Database\Exceptions;

use PDOException;

class ConnectionException extends \Core\Exceptions\CoreException
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
