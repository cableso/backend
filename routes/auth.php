<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [UserController::class, 'logout']);

    Route::post('/verifyEmail', [VerifyEmailController::class, 'store']);
});
