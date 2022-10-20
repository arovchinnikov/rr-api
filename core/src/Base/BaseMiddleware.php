<?php

declare(strict_types=1);

namespace Core\Base;

use Core\Base\Interfaces\ControllerInterface;
use Core\Base\Interfaces\MiddlewareInterface;

abstract class BaseMiddleware implements MiddlewareInterface
{
    protected ControllerInterface $controller;

    public function __construct(ControllerInterface $controller)
    {
        $this->controller = $controller;
    }
}
