<?php

declare(strict_types=1);

namespace Core;

use Core\Modules\Data\Config;
use Core\Modules\Data\Container;
use Core\Modules\Data\Env;
use Core\Modules\Data\Exceptions\EnvException;
use Core\Modules\RoadRunner\Worker;
use Core\Modules\Routing\Router;
use Core\Modules\Security\Security;
use JsonException;
use ReflectionException;
use Throwable;

class App
{
    private Worker $worker;
    private Router $router;

    private bool $initialized = false;

    /**
     * @throws ReflectionException
     */
    public function __construct()
    {
        /** @var Worker $worker */
        $worker = Container::get(Worker::class);
        $this->worker = $worker;
    }

    /**
     * @throws JsonException
     */
    public function run(): void
    {
        while ($request = $this->worker->waitRequest()) {
            try {
                $this->initialized ?: $this->init();

                $result = $this->router->dispatch($request);
                $this->worker->respond($result);
            } catch (Throwable $e) {
                $this->worker->handleException($e);
            }
        }
    }

    /**
     * @throws ReflectionException
     * @throws EnvException
     */
    private function init(): void
    {
        $this->initSettings();
        $this->initCoreComponents();

        $this->initialized = true;
    }

    /**
     * @throws ReflectionException|EnvException
     */
    private function initSettings(): void
    {
        /** @var Env $env */
        $env = Container::get(Env::class);
        $env->update();

        /** @var Config $config */
        $config = Container::get(Config::class);
        $config->update();
    }

    /**
     * @throws ReflectionException
     */
    private function initCoreComponents(): void
    {
        /** @var Router $router */
        $router = Container::get(Router::class);
        $this->router = $router;

        /** @var Security $security */
        $security = Container::get(Security::class);
        $security->setAppSecret(Env::get('APP_SECRET'));
        $security->init();
    }
}
