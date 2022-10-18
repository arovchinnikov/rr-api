<?php

declare(strict_types=1);

namespace Core\Base;

use Core\Base\Interfaces\BootloaderInterface;
use Core\Components\Config\ConfigBootloader;
use Core\Components\Config\EnvBootloader;
use Core\Components\Dependencies\Container;
use Core\Components\Dependencies\Interfaces\ContainerInterface;
use Core\Components\RoadRunner\Exceptions\RoadRunnerException;
use Core\Components\RoadRunner\Interfaces\WorkerInterface;
use Core\Components\RoadRunner\Worker;
use Core\Components\Routing\Interfaces\RouterInterface;
use Core\Components\Routing\Router;
use JsonException;
use Throwable;

abstract class BaseApp
{
    protected WorkerInterface $worker;
    protected RouterInterface $router;
    protected ContainerInterface $container;

    protected bool $loaded = false;

    protected array $defaultBootloaders = [
        EnvBootloader::class,
        ConfigBootloader::class
    ];

    public function __construct()
    {
        $this->container = new Container();
        $this->worker = $this->container->get(Worker::class);
    }

    /**
     * @return string[]
     */
    abstract public function bootloaders(): array;

    /**
     * @throws RoadRunnerException
     * @throws JsonException
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

    protected function init(): void
    {
        if ($this->loaded) {
            return;
        }

        foreach (array_merge($this->bootloaders(), $this->defaultBootloaders) as $bootloader) {
            $bootloader = new $bootloader();
            if ($bootloader instanceof BootloaderInterface) {
                $bootloader->run();
            }
        }

        $this->router = $this->container->get(Router::class);
        $this->loaded = true;
    }
}
