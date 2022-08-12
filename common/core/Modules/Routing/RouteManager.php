<?php

declare(strict_types=1);

namespace Core\Modules\Routing;

use Core\Modules\Data\Config;
use Core\Modules\Filesystem\Storage;
use Throwable;

class RouteManager
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
