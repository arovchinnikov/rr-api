<?php

declare(strict_types=1);

use App\App;

const ROOT = __DIR__;

require ROOT . '/vendor/autoload.php';
require ROOT . '/core/bootstrap.php';

$app = new App();
$app->run();
