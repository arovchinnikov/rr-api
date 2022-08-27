<?php

declare(strict_types=1);

namespace Core;

use Core\Modules\Data\Config;
use Core\Modules\Data\Env;
use Core\Modules\Debug\Log;
use Core\Modules\RoadRunner\HttpFactory;
use Core\Modules\RoadRunner\Worker;
use Core\Modules\Routing\Router;
use Core\Modules\Security\PasswordManager;
use JsonException;
use Throwable;

class App
{
    private HttpFactory $factory;

    private static Worker $worker;
    private Router $router;

    public function __construct()
    {
        $this->factory = new HttpFactory();
        Env::init();
        $config = new Config();
        $passwordManager = new PasswordManager();

        self::$worker = new Worker($this->factory);
        $this->router = new Router($this->factory);
    }

    public static function getWorker(): ?Worker
    {
        return self::$worker ?? null;
    }

    /**
     * @throws JsonException|Modules\RoadRunner\Exceptions\RoadRunnerException
     */
    public function run(): void
    {
        while ($request = self::$worker->waitRequest()) {
            try {
                $result = $this->router->dispatch($request);
                self::$worker->respond($result);
            } catch (Throwable $e) {
                Log::error((string)$e);
                /** Display an exception */
                self::$worker->respondString((string)$e);
                self::$worker->getWorker()->error((string)$e);
            }
        }
    }
}
