<?php

declare(strict_types=1);

namespace Core\Components\Routing\Interfaces;

use Core\Components\Http\Request;

interface RouteManagerInterface
{
    public function match(Request $request): ?RouteInterface;
}
