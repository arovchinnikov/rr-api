<?php

declare(strict_types=1);

use Core\App;
use Core\Modules\Debug\Log;
use Core\Modules\RoadRunner\Exceptions\RoadRunnerException;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

function d(...$var): void
{
    $worker = App::getWorker();

    try {
        $debugData = [];
        foreach (func_get_args() as $arg) {
            $cloner = new VarCloner();
            $dumper = new CliDumper();
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
 * @throws JsonException|RoadRunnerException
 */
function shutdown(): void
{
    $error = error_get_last();

    if (!empty($error)) {
        Log::error(implode(',', $error));
        App::getWorker()->respondString(implode(',', $error));

        exit();
    }
}
