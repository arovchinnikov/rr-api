<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Components\Data\Config;
use Core\Components\Filesystem\Storage;
use Core\Components\Http\Request;
use Core\Components\Routing\Interfaces\RouteCollectionInterface;
use Throwable;

class RouteCollection implements RouteCollectionInterface
{
    private string $routeStorage;

    private static array $routes = [];

    private static Throwable $e;

    public function __construct()
    {
        $this->routeStorage = ROOT . Config::get('main.route-storage');

        $this->collectRoutes();
    }

    public static function addRoute(Route $route): void
    {
        self::$routes[$route->getMethod()->value][] = $route;
    }

    public static function setErrors(Throwable $e): void
    {
        self::$e = $e;
    }

    public function getErrors(): ?Throwable
    {
        return self::$e;
    }

    public function match(Request $request): ?Route
    {
        $path = $request->url;
        $method = $request->method;

        foreach ($this->getRoutes($method->value) as $route) {
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
     * @return Route[]|Route
     */
    public function getRoutes(string $method = null): array|Route
    {
        $routes = isset($method) ? self::$routes[$method] : self::$routes;

        if (!is_array($routes)) {
            return [$routes];
        }

        return $routes;
    }

    private function collectRoutes(): void
    {
        $routeFiles = Storage::scanDir($this->routeStorage, true, true) ?? [];

        foreach ($routeFiles as $file) {
            $values = include $file;
        }
    }
}
