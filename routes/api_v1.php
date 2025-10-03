<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordManagementController;


Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/verify', [VerifyController::class, 'verify']);
    Route::post('/login', [LoginController::class, 'login']);
});


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/change-password', [PasswordManagementController::class, 'changePassword']);
    Route::post('/forgot-password', [PasswordManagementController::class, 'forgotPassword']);
});
