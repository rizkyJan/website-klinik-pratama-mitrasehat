<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\GalleryController;

// Auth routes (no middleware)
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// Protected admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Doctors CRUD
    Route::resource('/doctors', DoctorController::class);
    Route::post('/doctors/{doctor}/schedules', [DoctorController::class, 'storeSchedule'])->name('admin.doctors.schedules.store');
    Route::delete('/doctors/{doctor}/schedules/{schedule}', [DoctorController::class, 'destroySchedule'])->name('admin.doctors.schedules.destroy');

    // Services CRUD
    Route::resource('/services', ServiceController::class);

    // Faq CRUD
    Route::resource('/faqs', FaqController::class);

    // Promo CRUD
    Route::resource('/promos', PromoController::class);

    // Article CRUD
    Route::resource('/articles', ArticleController::class);

    // Gallery CRUD
    Route::resource('/galleries', GalleryController::class);
});