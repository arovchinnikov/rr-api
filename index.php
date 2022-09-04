<?php

declare(strict_types=1);

use Core\App;
use Core\Components\Debug\Log;

const ROOT = __DIR__;

require ROOT . '/vendor/autoload.php';
require ROOT . '/core/bootstrap.php';

$app = new App();

try {
    $app->run();
} catch (Throwable $exception) {
    Log::critical((string)$exception);
}
