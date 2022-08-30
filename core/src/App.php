<?php

declare(strict_types=1);

namespace Core;

use Core\Components\Data\Config;
use Core\Components\Data\Container;
use Core\Components\Data\Env;
use Core\Components\Data\Exceptions\EnvException;
use Core\Components\RoadRunner\Worker;
use Core\Components\Routing\Router;
use Core\Components\Security\Security;
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
     * @throws JsonException|Components\RoadRunner\Exceptions\RoadRunnerException
     */
    public function run(): void
    {
        while ($request = $this->worker->handleRequest()) {
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
        $security->setDefaultHashManager();
    }
}
