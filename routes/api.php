<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\PostController;
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

    Route::prefix('posts')->group(function () {
       Route::get('/feed', [PostController::class, 'index']);
       Route::get('{post}', [PostController::class, 'show']);
       Route::post('/', [PostController::class, 'store']);
       Route::put('{post}', [PostController::class, 'update']);
       Route::delete('{post}', [PostController::class, 'destroy']);
       Route::put('{post}/like', [PostController::class, 'toggleLike']);
       Route::post('{post}/comments', [CommentController::class, 'store']);
    });

    Route::prefix('comments')->group(function () {
       Route::put('{comment}', [CommentController::class, 'update']);
       Route::delete('{comment}', [CommentController::class, 'destroy']);
    });

    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index']);
        Route::get('{group}', [GroupController::class, 'show']);
        Route::get('/{group}/members', [GroupController::class, 'members']);
        Route::post('/', [GroupController::class, 'store']);
        Route::put('{group}', [GroupController::class, 'update']);
        Route::delete('{group}', [GroupController::class, 'destroy']);
        Route::post('{group}/join', [GroupController::class, 'join']);
        Route::post('{group}/approve/{user}', [GroupController::class, 'approveRequest']);
        Route::post('{group}/reject/{user}', [GroupController::class, 'rejectRequest']);
        Route::put('{group}/update-admin-role/{userId}', [GroupController::class, 'updateAdminRole']);
    });
});
