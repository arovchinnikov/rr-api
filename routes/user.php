<?php

use App\Modules\User\Api\Controllers\UserController;
use Core\Components\Routing\Route;

Route::get('/user/[id]', UserController::class, 'read');
Route::get('/user', UserController::class, 'list');
Route::post('/user', UserController::class, 'create');
