<?php

declare(strict_types=1);

namespace Core\Modules\RoadRunner;

use Core\Modules\Debug\Log;
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

    /**
     * @throws JsonException
     */
    public function respondString(string $text): void
    {
        $response = $this->httpFactory->createResponse();
        $response->getBody()->write($text);

        parent::respond($response);
    }

    /**
     * @throws JsonException
     */
    public function handleException(Throwable $exception): void
    {
        $message = (string)$exception;

        Log::error($message);
        /** Display an exception */
        $this->respondString($message);
        $this->getWorker()->error($message);
    }
}
