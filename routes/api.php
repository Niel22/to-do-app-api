<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\deviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dummyAPI;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('auth/login', [AuthController::class, 'store']);
Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/password/forgot', [AuthController::class, 'forgot']);
Route::post('auth/password/otp', [AuthController::class, 'confirm']);
Route::post('auth/password/reset/{token}', [AuthController::class, 'reset']);

Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');