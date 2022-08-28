<?php

use App\Common\Api\Controllers\ApiController;
use Core\Components\Routing\Route;

Route::get('/health-check', ApiController::class, 'healthCheck');
