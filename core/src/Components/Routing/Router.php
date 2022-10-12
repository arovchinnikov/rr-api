<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Components\Dependencies\Container;
use Core\Components\Http\Interfaces\RequestInterface;
use Core\Components\Http\Request;
use Core\Components\Http\Response;
use Core\Components\RoadRunner\HttpFactory;
use Core\Components\RoadRunner\Interfaces\HttpFactoryInterface;
use Core\Components\Routing\Exceptions\RoutingException;
use Core\Components\Routing\Interfaces\RouteCollectionInterface;
use Core\Components\Routing\Interfaces\RouterInterface;
use Core\Exceptions\AppException;
use Core\Exceptions\CoreException;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

class Router implements RouterInterface
{
    protected HttpFactoryInterface $httpFactory;
    protected RouteCollectionInterface $routeCollection;
    protected Container $container;

    public function __construct(HttpFactory $factory, RouteCollection $routeCollection, Container $container)
    {
        $this->httpFactory = $factory;
        $this->routeCollection = $routeCollection;
        $this->container = $container;
    }

    /**
     * @throws AppException
     * @throws RoutingException
     * @throws ReflectionException
     * @throws CoreException
     */
    public function dispatch(RequestInterface $request): ResponseInterface
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
        $controller = $this->container->resolve($route->getController(), false);

        $controller->request = $request;
        $controller->response = new Response();

        $action = $route->getAction();
        $actionParams = $this->container->getActionArgs($controller::class, $action, $request);
        $content = $controller->$action(...$actionParams) ?? [];

        return $this->httpFactory->createJsonResponse($content, $controller->response);
    }
}
