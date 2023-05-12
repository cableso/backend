<?php

use App\Http\Controllers\UserController;

Route::post('/register', [UserController::class, 'register']);
Route::delete('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);
