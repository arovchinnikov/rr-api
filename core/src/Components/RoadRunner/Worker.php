<?php

declare(strict_types=1);

namespace Core\Components\RoadRunner;

use Core\Components\Debug\Log;
use Core\Components\Http\Request;
use Core\Exceptions\Interfaces\AppExceptionInterface;
use JsonException;
use Spiral\RoadRunner\Http\PSR7Worker;
use Throwable;

class Worker extends PSR7Worker
{
    private readonly HttpFactory $httpFactory;

    public function __construct(HttpFactory $httpFactory)
    {
        $this->httpFactory = $httpFactory;
        $worker = \Spiral\RoadRunner\Worker::create();

        parent::__construct($worker, $httpFactory, $httpFactory, $httpFactory);
    }

    public function handleRequest(): ?Request
    {
        $request = $this->waitRequest();

        if (empty($request)) {
            return null;
        }

        return new Request($request);
    }

    /**
     * @throws JsonException|Exceptions\RoadRunnerException
     */
    public function respondString(string $text): void
    {
        $response = $this->httpFactory->createResponse();
        $response->getBody()->write($text);

        parent::respond($response);
    }

    /**
     * @throws JsonException
     * @throws Exceptions\RoadRunnerException
     */
    public function handleException(Throwable $exception): void
    {
        Log::error($exception);

        if ($exception instanceof AppExceptionInterface) {
            $content['code'] = $exception->getErrorCode();
        }
        $content['message'] = $exception->getMessage();
        $code = $exception->getCode() > 99 && $exception->getCode() < 599 ? $exception->getCode() : 500;

        self::respond($this->httpFactory->createErrorResponse($content, $code));
    }
}
