<?php

declare(strict_types=1);

namespace Core\Components\Routing\Exceptions;

use Core\Base\Exceptions\CoreException;

class RouteException extends CoreException
{
    /**
     * @throws RouteException
     */
    public static function controllerOrActionNotFound(string $controller, string $action)
    {
        throw new self('Controller: ' . $controller . ' or action: ' .  $action . ' not found.');
    }
}
