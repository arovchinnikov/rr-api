<?php

use App\Modules\User\Api\UserController;
use Core\Modules\Routing\Route;

Route::get('/user/[id]', UserController::class, 'read');
Route::get('/user', UserController::class, 'list');
Route::post('/user', UserController::class, 'create');
