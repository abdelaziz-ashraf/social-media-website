<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify-email', [AuthController::class, 'verifyEmail']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('users/{user}')->group(function () {
        Route::get('profile', [UserController::class, 'show']);
        Route::put('profile', [UserController::class, 'update']);
        Route::get('followers', [UserController::class, 'followers']);
        Route::get('followings', [UserController::class, 'followings']);
    });

    Route::post('users/{userToFollow}/follow', [UserController::class, 'follow']);
    Route::post('users/{userToUnfollow}/unfollow', [UserController::class, 'unfollow']);
});
