<?php

declare(strict_types=1);

namespace App;

use Core\Base\BaseApp;
use Core\Components\RoadRunner\Exceptions\RoadRunnerException;
use JsonException;
use Throwable;

class App extends BaseApp
{
    /**
     * @throws JsonException
     * @throws RoadRunnerException
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
