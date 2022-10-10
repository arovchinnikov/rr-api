<?php

declare(strict_types=1);

namespace Core\Base;

use Core\Components\Data\Config;
use Core\Components\Data\Env;
use Core\Components\RoadRunner\Interfaces\WorkerInterface;
use Core\Components\Routing\Interfaces\RouterInterface;

abstract class AbstractApp
{
    protected WorkerInterface $worker;
    protected RouterInterface $router;

    protected bool $inited = false;

    public function init(): void
    {
        if ($this->inited) {
            return;
        }

        Env::update();
        Config::update();

        $this->bootstrap();

        $this->inited = true;
    }

    abstract public function run(): void;
    abstract public function bootstrap(): void;
}
