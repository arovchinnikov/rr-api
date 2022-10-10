<?php

declare(strict_types=1);

namespace Core;

use Core\Base\AbstractApp;
use Core\Components\Data\Container;
use Core\Components\RoadRunner\Worker;
use Core\Components\Routing\Router;
use JsonException;
use Throwable;

class App extends AbstractApp
{
    public function __construct()
    {
        $this->worker = Container::get(Worker::class);
    }

    public function bootstrap(): void
    {
        $this->router = Container::get(Router::class);
    }

    /**
     * @throws JsonException
     * @throws Components\RoadRunner\Exceptions\RoadRunnerException
     */
    public function run(): void
    {
        while ($request = $this->worker->handleRequest()) {
            try {
                $this->init();

                $result = $this->router->dispatch($request);
                $this->worker->respond($result);
            } catch (Throwable $e) {
                $this->worker->handleException($e);
            }
        }
    }
}
