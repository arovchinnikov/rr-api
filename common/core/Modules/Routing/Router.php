<?php

declare(strict_types=1);

namespace Core\Modules\Routing;

use Core\Base\Classes\Http\BaseController;
use Core\Base\Classes\Http\ResponseObject;
use Core\Base\Exceptions\CoreException;
use Core\Base\Interfaces\Types\ToArray;
use Core\Modules\Data\Container;
use Core\Modules\Http\Enums\ResponseCode;
use Core\Modules\Http\Request;
use Core\Modules\Http\Response;
use Core\Modules\RoadRunner\HttpFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Throwable;

class Router
{
    private HttpFactory $httpFactory;
    private RouteManager $routeManager;

    public function __construct(HttpFactory $factory)
    {
        $this->httpFactory = $factory;
        $this->routeManager = new RouteManager();
    }

    public function dispatch(ServerRequestInterface|RequestInterface $requestData): ResponseInterface
    {
        $requestPath = $requestData->getUri()->getPath();
        $requestMethod = $requestData->getMethod();

        $foundRoute = $this->findRoute($requestPath, $requestMethod);

        if (empty($foundRoute)) {
            return $this->httpFactory->createErrorResponse(['message' => 'not found'], ResponseCode::notFound->value);
        }

        $request = new Request($requestData, $foundRoute->getParams());

        try {
            return $this->runAction($foundRoute, $request);
        } catch (Throwable $exception) {
            return $this->httpFactory->createErrorResponse(
                ['message' => $exception->getMessage()],
                $exception->getCode() > 99 ? $exception->getCode() : 500
            );
        }
    }

    /**
     * @throws ReflectionException
     * @throws CoreException
     */
    private function runAction(Route $route, Request $request): ResponseInterface
    {
        /** @var BaseController $controller */
        $controller = Container::resolve($route->getController(), false);

        $controller->request = $request;
        $controller->response = new Response();

        $actionName = $route->getAction();
        $content = $controller->$actionName();

        if ($content instanceof ResponseObject) {
            $content = $content->getAll();
        } elseif ($content instanceof toArray) {
            $content = $content->toArray();
        }

        return $this->httpFactory->createJsonResponse($content, $controller->response);
    }

    private function findRoute(string $path, string $method): ?Route
    {
        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }

        foreach ($this->routeManager->getRoutes($method) as $route) {
            if (empty($route)) {
                return null;
            }

            if ($route->matches($path)) {
                return $route;
            }
        }

        return null;
    }
}
