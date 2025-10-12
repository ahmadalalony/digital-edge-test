<?php

use App\Http\Controllers\Api\V1\Auth\LoginController as ApiLoginController;
use App\Http\Controllers\Api\V1\Auth\PasswordManagementController as ApiPasswordManagementController;
use App\Http\Controllers\Api\V1\Auth\RegisterController as ApiRegisterController;
use App\Http\Controllers\Api\V1\Auth\VerifyController as ApiVerifyController;
use App\Http\Controllers\Api\V1\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\V1\ProductAssignmentController as ApiProductAssignmentController;
use App\Http\Controllers\Api\V1\ProductController as ApiProductController;
use App\Http\Controllers\Api\V1\UserController as ApiUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:20,1')->prefix('v1')->group(function () {
    Route::post('/register', [ApiRegisterController::class, 'register']);
    Route::post('/verify', [ApiVerifyController::class, 'verify']);
    Route::post('/login', [ApiLoginController::class, 'login']);
});

Route::middleware(['auth:sanctum', 'throttle:20,1'])->prefix('v1')->group(function () {
    Route::post('/change-password', [ApiPasswordManagementController::class, 'changePassword']);
    Route::post('forgot-password', [ApiPasswordManagementController::class, 'forgotPassword']);
    Route::post('/reset-password', [ApiPasswordManagementController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum', 'role:Admin', 'throttle:20,1'])->prefix('v1/products')->group(function () {
    Route::get('/', [ApiProductController::class, 'index']);
    Route::post('/', [ApiProductController::class, 'store']);
    Route::put('/{id}', [ApiProductController::class, 'update']);
    Route::delete('/{id}', [ApiProductController::class, 'destroy']);

    Route::post('/assign', [ApiProductAssignmentController::class, 'assign']);
    Route::post('/unassign', [ApiProductAssignmentController::class, 'unassign']);

    Route::get('/export', [ApiProductController::class, 'export']);

});

Route::middleware(['auth:sanctum', 'throttle:20,1'])->prefix('v1/products')->group(function () {
    Route::get('/user-products', [ApiProductAssignmentController::class, 'userProducts'])->middleware('role:User|Admin');
});

Route::middleware(['auth:sanctum', 'role:Admin', 'throttle:20,1'])->prefix('v1/users')->group(function () {
    Route::get('/', [ApiUserController::class, 'index']);
    Route::get('/{id}', [ApiUserController::class, 'show']);
    Route::post('/', [ApiUserController::class, 'store']);
    Route::put('/{id}', [ApiUserController::class, 'update']);
    Route::delete('/{id}', [ApiUserController::class, 'destroy']);
    Route::post('/{user}/email', [ApiUserController::class, 'sendEmail']);
});

Route::middleware(['auth:sanctum', 'role:Admin', 'throttle:20,1'])->prefix('v1/users')->group(function () {
    Route::get('/export', [ApiUserController::class, 'export']);
});

Route::middleware(['auth:sanctum', 'role:Admin', 'throttle:20,1'])->prefix('v1/dashboard')->group(function () {
    Route::get('/overview', [ApiDashboardController::class, 'overview']);
});
