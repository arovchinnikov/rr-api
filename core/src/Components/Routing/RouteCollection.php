<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Components\Data\Config;
use Core\Components\Filesystem\Storage;
use Core\Components\Http\Request;
use Core\Components\Routing\Interfaces\RouteCollectionInterface;
use Core\Components\Routing\Interfaces\RouteInterface;

class RouteCollection implements RouteCollectionInterface
{
    private string $routeStorage;

    private static array $routes = [];

    public function __construct()
    {
        $this->routeStorage = ROOT . Config::get('main.route-storage');

        $this->collect();
    }

    public static function addRoute(Route $route): void
    {
        self::$routes[$route->getMethod()->value][] = $route;
    }

    public function match(Request $request): ?Route
    {
        $path = $request->url;
        $method = $request->method;

        foreach ($this->all($method->value) as $route) {
            if (empty($route)) {
                return null;
            }

            if ($route->matches($path)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * @return RouteInterface[]
     */
    public function all(string $method = null): array
    {
        $routes = isset($method) ? self::$routes[$method] : self::$routes;

        if (!is_array($routes)) {
            return [$routes];
        }

        return $routes;
    }

    private function collect(): void
    {
        $routeFiles = Storage::scanDir($this->routeStorage, true, true) ?? [];

        foreach ($routeFiles as $file) {
            $values = include $file;
        }
    }
}
