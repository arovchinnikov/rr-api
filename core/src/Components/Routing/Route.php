<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Components\Http\Enums\RequestMethod;
use Core\Components\Routing\Exceptions\RoutingException;
use Core\Components\Routing\Interfaces\RouteInterface;

class Route implements RouteInterface
{
    private string $url;
    private string $controller;
    private string $action;
    private array $params = [];
    private RequestMethod $method;

    private array $middlewares = [];

    /**
     * @throws RoutingException
     */
    public function __construct(string $url, RequestMethod $method, string $controller, string $action)
    {
        $this->setPattern($url);
        $this->setEndpoint($controller, $action);
        $this->method = $method;
    }

    public static function get(string $url, string $controller, string $action): Route
    {
        return self::add($url, RequestMethod::get, $controller, $action);
    }

    public static function post(string $url, string $controller, string $action): Route
    {
        return self::add($url, RequestMethod::post, $controller, $action);
    }

    public static function patch(string $url, string $controller, string $action): Route
    {
        return self::add($url, RequestMethod::patch, $controller, $action);
    }

    public static function put(string $url, string $controller, string $action): Route
    {
        return self::add($url, RequestMethod::put, $controller, $action);
    }

    public static function delete(string $url, string $controller, string $action): Route
    {
        return self::add($url, RequestMethod::delete, $controller, $action);
    }

    private static function add(string $url, RequestMethod $method, string $controller, string $action): Route
    {
        return RouteManager::addRoute(new Route($url, $method, $controller, $action));
    }

    public function matches(string $path): bool
    {
        if (preg_match($this->url, $path, $rawParams)) {
            $params = [];
            foreach ($rawParams as $name => $value) {
                if (is_string($name)) {
                    $params[$name] = $value;
                }
            }

            $this->params = $params;

            return true;
        }

        return false;
    }

    public function middleware(string $classname, array $options = []): self
    {
        $this->middlewares[$classname] = $options;

        return $this;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): RequestMethod
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    private function setPattern(string $path): void
    {
        $path = explode('/', $path);
        $pattern = [];
        foreach ($path as $pathPart) {
            /** Matches [var] */
            if (preg_match("/^\[[-a-z-0-9-_]+\]$/", $pathPart)) {
                $pathPart = str_replace(['[', ']'], '', $pathPart);
                $pathPart = "(?P<" . $pathPart . ">\w+)";
            }
            $pattern[] = $pathPart;
        }

        $pattern = implode('\/', $pattern);
        $this->url =  '/^' . $pattern . '$/';
    }

    /**
     * @throws RoutingException
     */
    private function setEndpoint(string $controller, string $action): void
    {
        if (!method_exists($controller, $action)) {
            RoutingException::controllerOrActionNotFound($controller, $action);
        }

        $this->controller = $controller;
        $this->action = $action;
    }
}
