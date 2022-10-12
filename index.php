<?php

declare(strict_types=1);

use App\App;
use Core\Components\Config\Config;
use Core\Components\Config\Env;

const ROOT = __DIR__;

require ROOT . '/vendor/autoload.php';
require ROOT . '/core/bootstrap.php';

Config::setPath(ROOT . '/app/common/config');
Env::setFile(ROOT . '/.env');

$app = new App();
$app->run();
