<?php

declare(strict_types=1);

namespace Core\Base;

use Core\Components\Config\Config;
use Core\Components\Config\Env;
use Core\Components\Dependencies\Container;
use Core\Components\Dependencies\Interfaces\ContainerInterface;
use Core\Components\RoadRunner\Interfaces\WorkerInterface;
use Core\Components\RoadRunner\Worker;
use Core\Components\Routing\Interfaces\RouterInterface;
use Core\Components\Routing\Router;

abstract class BaseApp
{
    protected WorkerInterface $worker;
    protected RouterInterface $router;
    protected ContainerInterface $container;

    protected bool $loaded = false;

    public function __construct()
    {
        $this->container = new Container();
        $this->worker = $this->container->get(Worker::class);
    }

    public function init(): void
    {
        if ($this->loaded) {
            return;
        }

        Env::update();
        Config::update();

        $this->router = $this->container->get(Router::class);

        $this->loaded = true;
    }

    abstract public function run(): void;
}
