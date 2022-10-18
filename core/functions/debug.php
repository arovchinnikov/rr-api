<?php

declare(strict_types=1);

use Core\Components\Dependencies\Container;
use Core\Components\RoadRunner\Exceptions\RoadRunnerException;
use Core\Components\RoadRunner\Worker;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

function d(...$var): void
{
    $worker = (new Container)->get(Worker::class);

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
        $worker = (new Container)->get(Worker::class);
        $worker->respondString(implode(',', $error));

        exit();
    }
}
