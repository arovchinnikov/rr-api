<?php

declare(strict_types=1);

namespace Core\Modules\Routing;

use Core\Modules\Http\Enums\ResponseCode;
use Core\Modules\Http\Request;
use Core\Modules\RoadRunner\Exceptions\RoadRunnerException;
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

    /**
     * @throws RoadRunnerException
     */
    public function dispatch(ServerRequestInterface|RequestInterface $requestData): ResponseInterface
    {
        $request = new Request($requestData);
        return $this->httpFactory->createJsonResponse(['success' => true], ResponseCode::ok);
    }
}
