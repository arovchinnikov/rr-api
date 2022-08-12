<?php

declare(strict_types=1);

namespace Core\Modules\Routing;

use Core\Modules\Http\Enums\RequestMethod;
use Core\Modules\Routing\Exceptions\RouteException;
use Throwable;

class Route
{
    private string $rule;
    private string $controller;
    private string $action;
    private array $params = [];
    private RequestMethod $method;

    /**
     * @throws RouteException
     */
    public function __construct(string $rule, RequestMethod $method, string $controller, string $action)
    {
        $this->setPattern($rule);
        $this->setEndpoint($controller, $action);
        $this->method = $method;
    }

    public static function get(string $rule, string $controller, string $action): void
    {
        self::add($rule, RequestMethod::get, $controller, $action);
    }

    public static function post(string $rule, string $controller, string $action): void
    {
        self::add($rule, RequestMethod::post, $controller, $action);
    }

    public static function patch(string $rule, string $controller, string $action): void
    {
        self::add($rule, RequestMethod::patch, $controller, $action);
    }

    public static function put(string $rule, string $controller, string $action): void
    {
        self::add($rule, RequestMethod::put, $controller, $action);
    }

    public static function delete(string $rule, string $controller, string $action): void
    {
        self::add($rule, RequestMethod::delete, $controller, $action);
    }

    public function matches(string $path): bool
    {
        if (preg_match($this->rule, $path, $rawParams)) {
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

    private static function add(string $rule, RequestMethod $method, string $controller, string $action): void
    {
        try {
            RouteManager::addRoute(new Route($rule, $method, $controller, $action));
        } catch (Throwable $exception) {
            RouteManager::setErrors($exception);
        }
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
        $this->rule =  '/^' . $pattern . '$/';
    }

    /**
     * @throws RouteException
     */
    private function setEndpoint(string $controller, string $action): void
    {
        if (!method_exists($controller, $action)) {
            RouteException::controllerOrActionNotFound($controller, $action);
        }

        $this->controller = $controller;
        $this->action = $action;
    }
}
