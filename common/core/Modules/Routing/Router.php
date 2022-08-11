<?php

declare(strict_types=1);

namespace Core\Modules\Routing;

use App\Http\Controllers\TestController;
use Core\Modules\Http\Request;
use Core\Modules\RoadRunner\HttpFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private HttpFactory $httpFactory;

    public function __construct(HttpFactory $factory)
    {
        $this->httpFactory = $factory;
    }

    public function dispatch(ServerRequestInterface|RequestInterface $requestData): ResponseInterface
    {
        $request = new Request($requestData);

        $controller = new TestController($request);
        $content = $controller->index();

        return $this->httpFactory->createJsonResponse($content, $controller->response);
    }
}
