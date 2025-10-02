<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [RegisterController::class, 'register'])->name('api.v1.register');
    Route::post('/verify', [VerifyController::class, 'verify'])->name('api.v1.verify');
});