<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordManagementController;
use App\Http\Controllers\product\ProductController;
use App\Http\Controllers\product\ProductAssignmentController;
use App\Http\Controllers\User\UserController;

use App\Http\Controllers\Admin\DashboardController;


Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/verify', [VerifyController::class, 'verify']);
    Route::post('/login', [LoginController::class, 'login']);
});


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/change-password', [PasswordManagementController::class, 'changePassword']);
    Route::post('forgot-password', [PasswordManagementController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordManagementController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum'])->prefix('v1/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});


Route::middleware(['auth:sanctum'])->prefix('v1/products')->group(function () {
    Route::middleware('role:Admin')->group(function () {
        Route::post('/assign', [ProductAssignmentController::class, 'assign']);
        Route::post('/unassign', [ProductAssignmentController::class, 'unassign']);
    });

    Route::get('/user-products', [ProductAssignmentController::class, 'userProducts'])->middleware('role:User|Admin');
});

Route::middleware(['auth:sanctum', 'role:Admin'])->prefix('v1/users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});


Route::middleware(['auth:sanctum', 'role:Admin'])->prefix('v1/dashboard')->group(function () {
    Route::get('/overview', [DashboardController::class, 'overview']);
});