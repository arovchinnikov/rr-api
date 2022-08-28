<?php

use App\Common\Api\Controllers\ApiController;
use Core\Modules\Routing\Route;

Route::get('/health-check', ApiController::class, 'healthCheck');
