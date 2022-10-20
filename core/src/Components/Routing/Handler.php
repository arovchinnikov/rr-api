<?php

declare(strict_types=1);

namespace Core\Components\Routing;

use Core\Base\Interfaces\MiddlewareInterface;
use Core\Components\Dependencies\Container;
use Core\Components\Dependencies\Interfaces\ContainerInterface;
use Core\Components\Http\Request;
use Core\Components\Http\Response;
use Core\Components\RoadRunner\Exceptions\RoadRunnerException;
use Core\Components\RoadRunner\HttpFactory;
use Core\Components\RoadRunner\Interfaces\HttpFactoryInterface;
use Core\Components\Routing\Interfaces\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;

class Handler implements HandlerInterface
{
    protected HttpFactoryInterface $httpFactory;
    protected ContainerInterface $container;

    protected array $middlewares = [];

    protected string $controller;
    protected string $action;

    public function __construct(HttpFactory $httpFactory, Container $container)
    {
        $this->httpFactory = $httpFactory;
        $this->container = $container;
    }

    public function setEndpoint(Route $route): self
    {
        $this->controller = $route->getController();
        $this->action = $route->getAction();

        return $this;
    }

    public function setMiddlewares(array $middlewares): self
    {
        $this->middlewares = $middlewares;

        return $this;
    }

    /**
     * @throws ReflectionException
     * @throws RoadRunnerException
     */
    public function handle(Request $request): ResponseInterface
    {
        $controller = $this->container->resolve($this->controller, false);
        $action = $this->action;

        $controller->request = $request;
        $controller->response = new Response();

        /** @var MiddlewareInterface $middleware */
        foreach ($this->middlewares as $middleware) {
            $middleware = new $middleware($controller);
            $middleware->execute($request);
        }

        $actionParams = $this->container->getActionArgs($controller::class, $action, $request);
        $content = $controller->$action(...$actionParams) ?? [];

        return $this->httpFactory->createJsonResponse($content, $controller->response);
    }
}
