<?php

declare(strict_types=1);

use App\App;
use Core\Components\Config\Config;
use Core\Components\Config\Env;
use Core\Components\Debug\Log;

const ROOT = __DIR__;

require ROOT . '/vendor/autoload.php';
require ROOT . '/core/bootstrap.php';

Config::setPath(ROOT . '/app/common/config');
Env::setFile(ROOT . '/.env');

$app = new App();

try {
    $app->run();
} catch (Throwable $exception) {
    Log::critical((string)$exception);
}
