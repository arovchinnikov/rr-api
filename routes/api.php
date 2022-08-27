<?php

use App\BaseApi\Http\Controllers\ApiController;
use Core\Modules\Routing\Route;

Route::get('/health-check', ApiController::class, 'healthCheck');
