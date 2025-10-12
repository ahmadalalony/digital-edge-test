<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\ActivityLogController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\NotificationController as DashboardNotificationController;
use App\Http\Controllers\Dashboard\ProductViewController;
use App\Http\Controllers\Dashboard\UserViewController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\User\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Broadcast::routes(['middleware' => ['web', 'auth']]);

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest:web');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login_store')->middleware('guest:web');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth:web');

Route::middleware(['auth:web', 'throttle:20,1'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:Admin', 'throttle:20,1'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin_dashboard');
    Route::get('dashboard/lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
        // return 'Locale set to: ' . Session::get('locale');
    })->name('lang_switch');
    Route::get('/users', [UserViewController::class, 'index'])->name('admin_users_index');
    Route::get('/users/create', [UserViewController::class, 'create'])->name('admin_users_create');
    Route::get('/users/{id}/edit', [UserViewController::class, 'edit'])->name('admin_users_edit');
    Route::post('/users', [UserController::class, 'store'])->name('admin_users_store');
    Route::post('/users/{id}/change-password', [UserController::class, 'changePassword'])->name('admin_users_change_password');
    Route::post('/users/{id}/admin-change-password', [UserController::class, 'adminChangePassword'])->name('admin_users_admin_change_password');
    Route::put('/users/{id}', [UserViewController::class, 'update'])->name('admin_users_update');
    Route::get('/users/{id}/email', [UserViewController::class, 'emailForm'])->name('admin_users_email_form');
    Route::post('/users/{id}/email', [UserViewController::class, 'sendEmail'])->name('admin_users_send_email');
    Route::get('/products', [ProductViewController::class, 'index'])->name('admin_products_index');
    Route::get('/products/create', [ProductViewController::class, 'create'])->name('admin_products_create');
    Route::get('/products/{id}/edit', [ProductViewController::class, 'edit'])->name('admin_products_edit');
    Route::get('/products/export', [ProductController::class, 'export'])->name('admin_products_export');
    Route::get('/users/export', [UserController::class, 'export'])->name('admin_users_export');

    // Activity logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin_activity_logs_index');

    // Notifications
    Route::get('/notifications', [DashboardNotificationController::class, 'index'])->name('admin_notifications_index');
    Route::post('/notifications/{id}/read', [DashboardNotificationController::class, 'markAsRead'])->name('admin_notifications_read');
    Route::post('/notifications/read-all', [DashboardNotificationController::class, 'markAllAsRead'])->name('admin_notifications_read_all');
    Route::get('/notifications/count', [DashboardNotificationController::class, 'unreadCount'])->name('admin_notifications_count');

    Route::get('/users/data', [UserController::class, 'index'])->name('admin_users_data');
    Route::get('/products/data', [ProductController::class, 'index'])->name('admin_products_data');

    Route::post('/products', [ProductController::class, 'store'])->name('admin_products_store');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin_products_update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin_products_destroy');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin_users_destroy');
});

if (app()->isLocal()) {
    Route::get('/dev-login/{id}', function ($id) {
        $user = User::find($id);

        if (! $user) {
            return "User with ID {$id} not found.";
        }

        Auth::guard('web')->login($user);

        return redirect('/dashboard');
    });
}
