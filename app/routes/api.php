<?php

use App\AdminPanel\Api\Controllers\ServerStatusController;
use App\Common\Infrastructure\Middlewares\AuthMiddleware;
use Core\Components\Routing\Route;

Route::get('/health-check', ServerStatusController::class, 'healthCheck')
    ->middleware(AuthMiddleware::class, ['role' => 'admin']);
