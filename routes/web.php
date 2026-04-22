<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\FuelConsumptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// Authentication routes (Laravel built-in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
                ->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    
    Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
                ->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // SEARCH
    Route::get('/api/search', [SearchController::class, 'global'])->name('search.global');
    Route::get('/api/notifications', [SearchController::class, 'notifications'])->name('notifications.fetch');

    // ==================== BOOKINGS (All users can see, creator can edit pending) ====================
    Route::resource('bookings', BookingController::class);
    
    // Custom booking actions
    Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve'])
        ->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])
        ->name('bookings.reject');
    Route::post('/bookings/{booking}/complete', [BookingController::class, 'complete'])
        ->name('bookings.complete');

    // ==================== VEHICLES (All users can see, admin can edit/delete) ====================
    Route::resource('vehicles', VehicleController::class);

    // ==================== APPROVALS (Only admin/approver can see and manage) ====================
    Route::middleware('approver')->group(function () {
        Route::resource('approvals', ApprovalController::class)
            ->only(['index', 'show']);
        
        // Custom approval actions
        Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])
            ->name('approvals.approve');
        Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])
            ->name('approvals.reject');
    });

    // ==================== ACTIVITY LOGS (Users can see their own activity) ====================
    Route::resource('activity-logs', ActivityLogController::class)
        ->only(['index', 'show']);
    Route::get('/my-activity', [ActivityLogController::class, 'myActivity'])
        ->name('activity-logs.my-activity');
    Route::get('/activity-logs/filter/module/{module}', [ActivityLogController::class, 'filterByModule'])
        ->name('activity-logs.filter-module');
    Route::get('/activity-logs/filter/action/{action}', [ActivityLogController::class, 'filterByAction'])
        ->name('activity-logs.filter-action');

    // ==================== ADMIN ONLY ====================
    Route::middleware('admin')->group(function () {
        
        // REGIONS
        Route::resource('regions', RegionController::class);

        // DEPARTMENTS
        Route::resource('departments', DepartmentController::class);

        // USERS
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/update-password', [UserController::class, 'updatePassword'])
            ->name('users.update-password');

        // FUEL CONSUMPTION
        Route::resource('fuel-consumptions', FuelConsumptionController::class);
        Route::get('/fuel-consumptions/statistics/summary', [FuelConsumptionController::class, 'statistics'])
            ->name('fuel-consumptions.statistics');

        // SERVICE SCHEDULES
        Route::resource('service-schedules', \App\Http\Controllers\ServiceScheduleController::class);
        Route::post('/service-schedules/{serviceSchedule}/mark-completed', [\App\Http\Controllers\ServiceScheduleController::class, 'markCompleted'])
            ->name('service-schedules.markCompleted');
        Route::get('/service-schedules/statistics/stats', [\App\Http\Controllers\ServiceScheduleController::class, 'stats'])
            ->name('service-schedules.stats');

        // ACTIVITY LOGS ADMIN FEATURES
        Route::delete('/activity-logs/clear', [ActivityLogController::class, 'clear'])
            ->name('activity-logs.clear');
        Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])
            ->name('activity-logs.export');

        // BOOKING REPORTS (Admin Only)
        Route::get('/booking-reports', [App\Http\Controllers\BookingReportController::class, 'index'])
            ->name('booking-reports.index');
        Route::get('/booking-reports/export', [App\Http\Controllers\BookingReportController::class, 'export'])
            ->name('booking-reports.export');
    });
});
