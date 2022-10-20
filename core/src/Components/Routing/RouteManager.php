<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Components\Config\Config;
use Core\Components\Filesystem\Storage;
use Core\Components\Http\Request;
use Core\Components\Routing\Interfaces\RouteManagerInterface;
use Core\Components\Routing\Interfaces\RouteInterface;

class RouteManager implements RouteManagerInterface
{
    private string $routeStorage;

    private static array $routes = [];

    public function __construct()
    {
        $this->routeStorage = ROOT . '/app/routes';

        $this->collect();
    }

    public static function addRoute(Route $route): Route
    {
        $link = &$route;

        self::$routes[$route->getMethod()->value][] = $link;

        return $link;
    }

    public function match(Request $request): ?Route
    {
        $path = $request->url;
        $method = $request->method;

        foreach (self::$routes[$method->value] as $route) {
            if (empty($route)) {
                return null;
            }

            if ($route->matches($path)) {
                return $route;
            }
        }

        return null;
    }

    private function collect(): void
    {
        $routeFiles = Storage::scanDir($this->routeStorage, true, true) ?? [];

        foreach ($routeFiles as $file) {
            $values = include $file;
        }
    }
}
