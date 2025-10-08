<?php

use App\Http\Controllers\Admin\UserViewController;
use App\Http\Controllers\Admin\DashboardController;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/users', [UserViewController::class, 'index'])->name('dashboard.users.index');
Route::get('/dashboard/users/{id}/edit', [UserViewController::class, 'edit'])->name('dashboard.users.edit');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserViewController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}/edit', [UserViewController::class, 'edit'])->name('admin.users.edit');
});


if (app()->isLocal()) {
    Route::get('/dev-login/{id}', function ($id) {
        $user = User::find($id);

        if (!$user) {
            return "User with ID {$id} not found.";
        }

        Auth::guard('web')->login($user);

        return redirect('/dashboard');
    });
}