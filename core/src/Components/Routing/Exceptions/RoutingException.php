<?php

declare(strict_types=1);

namespace Core\Components\Routing\Exceptions;

use Core\Components\Http\Enums\ResponseCode;
use Core\Exceptions\CoreException;

class RoutingException extends CoreException
{
    /**
     * @throws RoutingException
     */
    public static function notFound(): void
    {
        throw new self('not found', ResponseCode::notFound);
    }
    /**
     * @throws RoutingException
     */
    public static function controllerOrActionNotFound(string $controller, string $action): void
    {
        throw new self('Controller: ' . $controller . ' or action: ' .  $action . ' not found.');
    }
}
