<?php

declare(strict_types=1);

namespace Core\Components\Http\Interfaces;

use Core\Components\Routing\Interfaces\RouteInterface;

interface RequestInterface
{
    public function setRoute(RouteInterface $route): void;
}
