<?php

declare(strict_types=1);

namespace Core\Modules\RoadRunner;

use JsonException;
use Spiral\RoadRunner\Http\PSR7Worker;

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
     * @throws JsonException|Exceptions\RoadRunnerException
     */
    public function respondString(string $text): void
    {
        $response = $this->httpFactory->createResponse();
        $response->getBody()->write($text);

        parent::respond($response);
    }
}
