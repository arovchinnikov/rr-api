<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Components\Data\Container;
use Core\Components\Http\Request;
use Core\Components\Http\Response;
use Core\Components\RoadRunner\HttpFactory;
use Core\Components\Routing\Exceptions\RoutingException;
use Core\Components\Routing\Interfaces\RouteCollectionInterface;
use Core\Exceptions\AppException;
use Core\Exceptions\CoreException;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

class Router
{
    private HttpFactory $httpFactory;
    private RouteCollectionInterface $routeCollection;

    public function __construct(HttpFactory $factory, RouteCollection $routeCollection)
    {
        $this->httpFactory = $factory;
        $this->routeCollection = $routeCollection;
    }

    /**
     * @throws AppException
     * @throws RoutingException
     * @throws ReflectionException
     * @throws CoreException
     */
    public function dispatch(Request $request): ResponseInterface
    {
        $route = $this->routeCollection->match($request);
        if (empty($route)) {
            RoutingException::notFound();
        }

        $request->initUrlParams($route->getParams());
        return $this->runAction($route, $request);
    }

    /**
     * @throws ReflectionException
     * @throws CoreException
     * @throws AppException
     */
    private function runAction(Route $route, Request $request): ResponseInterface
    {
        $controller = Container::resolve($route->getController(), false);

        $controller->request = $request;
        $controller->response = new Response();

        $action = $route->getAction();
        $actionParams = Container::resolveMethod($controller::class, $action, $request);
        $content = $controller->$action(...$actionParams) ?? [];

        return $this->httpFactory->createJsonResponse($content, $controller->response);
    }
}
