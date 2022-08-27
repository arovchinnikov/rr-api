<?php

declare(strict_types=1);

namespace Core\Modules\Routing;

use BackedEnum;
use Core\Base\Classes\Http\BaseController;
use Core\Base\DataValues\Interfaces\BaseValue;
use Core\Base\Exceptions\AppException;
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

    public function dispatch(ServerRequestInterface $requestData): ResponseInterface
    {
        $foundRoute = $this->findRoute($requestData);

        if (empty($foundRoute)) {
            return $this->httpFactory->createErrorResponse(['message' => 'not found'], ResponseCode::notFound->value);
        }

        $request = new Request($requestData, $foundRoute->getParams());

        try {
            return $this->runAction($foundRoute, $request);
        } catch (AppException $appException) {
            /** Business logic errors, with error code */
            return $this->httpFactory->createErrorResponse(
                [
                    'error_code' => $appException->getErrorCode(),
                    'message' => $appException->getMessage()
                ],
                $appException->getCode() > 99 && $appException->getCode() < 599 ? $appException->getCode() : 500
            );
        } catch (Throwable $exception) {
            return $this->httpFactory->createErrorResponse(
                ['message' => $exception->getMessage()],
                $exception->getCode() > 99 && $exception->getCode() < 599 ? $exception->getCode() : 500
            );
        }
    }

    /**
     * @throws ReflectionException
     * @throws CoreException
     * @throws AppException
     */
    private function runAction(Route $route, Request $request): ResponseInterface
    {
        /** @var BaseController $controller */
        $controller = Container::resolve($route->getController(), false);

        $controller->request = $request;
        $controller->response = new Response();

        $actionName = $route->getAction();
        $actionParams = Container::resolveControllerMethod($controller::class, $actionName, $request);
        $content = $controller->$actionName(...$actionParams) ?? [];

        $content = $this->prepareContent($content);

        return $this->httpFactory->createJsonResponse($content, $controller->response);
    }

    private function prepareContent(array|ToArray $values): array
    {
        if ($values instanceof ToArray) {
            $values = $values->toArray();
        }

        $return = [];
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $return[$key] = $this->prepareContent($value);
                continue;
            }

            if (!is_object($value)) {
                $return[$key] = $value;
                continue;
            }

            if ($value instanceof ToArray) {
                $return[$key] = $this->prepareContent($value->toArray());
                continue;
            }

            if ($value instanceof BaseValue) {
                $return[$key] = $value->getValue();
                continue;
            }

            if ($value instanceof BackedEnum) {
                $return[$key] = $value->value;
                continue;
            }

            return [];
        }

        return $return;
    }

    private function findRoute(ServerRequestInterface|RequestInterface $requestData): ?Route
    {
        $path = $requestData->getUri()->getPath();
        $method = $requestData->getMethod();

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
