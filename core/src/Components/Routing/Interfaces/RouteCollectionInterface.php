<?php

declare(strict_types=1);

namespace Core\Components\Routing\Interfaces;

use Core\Components\Http\Request;

interface RouteCollectionInterface8
{
    public function match(Request $request): ?RouteInterface;

    /** @return RouteInterface[] */
    public function all(string $method = null): array;
}
