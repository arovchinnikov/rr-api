<?php

declare(strict_types=1);

use Core\Components\Data\Container;
use Core\Components\Debug\Log;
use Core\Components\RoadRunner\Exceptions\RoadRunnerException;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Core\Components\RoadRunner\Worker;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

function d(...$var): void
{
    $worker = Container::get(Worker::class);

    try {
        $debugData = [];
        foreach (func_get_args() as $arg) {
            $cloner = new VarCloner();
            $dumper = new HtmlDumper();
            $debugData[] =  $dumper->dump($cloner->cloneVar($arg), true);
        }

        $worker->respondString(implode(PHP_EOL, $debugData));
        $worker->getWorker()->stop();
    } catch (Throwable $e) {
        $worker->getWorker()->error((string)$e);
    }
    exit();
}

/**
 * @throws JsonException
 * @throws RoadRunnerException
 */
function shutdown(): void
{
    $error = error_get_last();

    if (!empty($error)) {
        /** @var Worker $worker */
        $worker = Container::get(Worker::class);

        Log::error(implode(',', $error));
        $worker->respondString(implode(',', $error));

        exit();
    }
}
