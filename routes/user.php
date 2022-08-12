<?php

use App\BaseApi\Controllers\ApiController;
use Core\Modules\Routing\Route;

Route::get('/health-check', ApiController::class, 'healthCheck');
Route::get('/healsth-check', ApiController::class, 'healthCheck');
