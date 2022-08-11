<?php

declare(strict_types=1);

use Core\App;
use Core\Modules\Debug\Log;

const ROOT = __DIR__;

require ROOT . '/vendor/autoload.php';
require ROOT . '/common/bootstrap.php';

$app = new App();

try {
    $app->run();
} catch (Throwable $exception) {
    Log::critical((string)$exception);
}
