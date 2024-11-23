<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\deviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dummyAPI;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\Notification;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'guest'], function () {
    Route::post('auth/login', [AuthController::class, 'store']);
    Route::post('auth/register', [AuthController::class, 'create']);
    Route::post('auth/password/forgot', [AuthController::class, 'forgot']);
    Route::post('auth/password/otp', [AuthController::class, 'confirm']);
    Route::post('auth/password/reset/{token}', [AuthController::class, 'reset']);
});

Route::group(['middleware' => 'auth:sanctum'], function(){

    // User Profile
    Route::get('profile', [UserController::class, 'getProfile']);
    Route::put('profile/update', [UserController::class, 'updateProfile']);

    // Task
    Route::apiResource('task', TaskController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::get('task/{task}/complete', [TaskController::class, 'complete']);
    Route::get('tasks/completed', [TaskController::class, 'completed']);

    // Notofications
    Route::get('notifications/count', [NotificationController::class, 'count']);
    Route::get('notifications/{notification}', [NotificationController::class, 'show']);
    Route::put('notifications/{notification}', [NotificationController::class, 'read']);
    Route::delete('notification', [NotificationController::class, 'destroy']);


    Route::post('auth/logout', [AuthController::class, 'logout']);
});