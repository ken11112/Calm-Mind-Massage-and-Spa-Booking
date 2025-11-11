<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

// Authentication routes
Auth::routes();

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Facebook webhook endpoints (verification + events)
use App\Http\Controllers\FacebookWebhookController;

Route::get('/facebook/webhook', [FacebookWebhookController::class, 'verify']);
Route::post('/facebook/webhook', [FacebookWebhookController::class, 'receive']);

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Services
    Route::get('services', [ServiceController::class, 'index'])->name('admin.services');

    // Gallery
    Route::get('gallery', [GalleryController::class, 'index'])->name('admin.gallery');

    // Bookings
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('admin.bookings');

    // Facebook settings
    Route::get('facebook-settings', [\App\Http\Controllers\Admin\FacebookSettingsController::class, 'index'])->name('admin.facebook.index');
    Route::post('facebook-settings', [\App\Http\Controllers\Admin\FacebookSettingsController::class, 'update'])->name('admin.facebook.update');
});
