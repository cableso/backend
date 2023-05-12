<?php

use App\Http\Controllers\UserController;

Route::post('/register', [UserController::class, 'register']);
