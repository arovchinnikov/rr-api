<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Components\Http\Interfaces\RequestInterface;
use Core\Components\Routing\Exceptions\RoutingException;
use Core\Components\Routing\Interfaces\HandlerInterface;
use Core\Components\Routing\Interfaces\RouteManagerInterface;
use Core\Components\Routing\Interfaces\RouterInterface;
use Core\Exceptions\CoreException;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

class Router implements RouterInterface
{
    protected RouteManagerInterface $routeManager;
    protected HandlerInterface $handler;

    protected array $middlewares = [];

    public function __construct(RouteManager $routeManager, Handler $handler)
    {
        $this->routeManager = $routeManager;
        $this->handler = $handler;
    }

    /**
     * @throws RoutingException
     * @throws CoreException
     * @throws ReflectionException
     */
    public function dispatch(RequestInterface $request): ResponseInterface
    {
        $route = $this->routeManager->match($request);
        if (empty($route)) {
            RoutingException::notFound();
        }

        /**
         * пробросить middlewares из App, array_merge(route->middlewares, appMiddlewares)
         */

        return $this->handler
            ->setMiddlewares($this->middlewares)
            ->setEndpoint($route)
            ->handle($request);
    }
}
