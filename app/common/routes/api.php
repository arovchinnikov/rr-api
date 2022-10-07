<?php

use App\Common\Api\Controllers\ApiToolsController;
use Core\Components\Routing\Route;

Route::get('/health-check', ApiToolsController::class, 'healthCheck');
