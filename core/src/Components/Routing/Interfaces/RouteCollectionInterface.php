<?php

namespace Core\Components\Routing\Interfaces;

use Core\Components\Http\Request;
use Core\Components\Routing\Route;

interface RouteCollectionInterface
{
    public function match(Request $request): ?Route;
}