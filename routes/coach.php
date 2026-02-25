<?php

use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Coach\DashboardController;
use App\Http\Controllers\Coach\ProfileController;
use App\Http\Controllers\Coach\BlogController;
use App\Http\Controllers\Coach\InteractionController;
use App\Http\Controllers\Coach\MediaGalleryController;
use App\Http\Controllers\Coach\MessageRequestController;

/*
|--------------------------------------------------------------------------
| Public Coach Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login/user', [OtpController::class, 'showLoginForm'])->name('user.login');
Route::post('/send-otp/user', [OtpController::class, 'sendOtp'])->name('auth.otp.send');
Route::post('/verify-otp/user', [OtpController::class, 'verifyOtp'])->name('auth.otp.verify');
Route::post('/logout/user', [OtpController::class, 'logout'])->name('coach.logout');
/*
|--------------------------------------------------------------------------
| Authenticated Coach Workspace
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::prefix('coach')->as('coach.')->group(function() {

    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
        
        // Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
        });

       Route::resource('blogs', BlogController::class)->names('blogs');
        
        // Custom Blog Status Toggle
        Route::post('blogs/update-status', [BlogController::class, 'updateStatus'])->name('blogs.update-status');
        Route::prefix('notifications')->as('notifications.')->group(function () {
            Route::get('/mark-as-read', [DashboardController::class, 'markAsRead'])->name('markAsRead');
        });

        Route::resource('media', MediaGalleryController::class);
        
        Route::controller(MediaGalleryController::class)->group(function () {
            Route::post('/media/upload', 'upload')->name('media.upload');
        });
        
        Route::get('/chat/{seekerId}/fetch', [InteractionController::class, 'fetchMessages'])->name('interactions.fetch');
        Route::controller(InteractionController::class)->group(function () {
            Route::get('/messages', 'index')->name('interactions.index');
            Route::get('/messages/{id}', 'show')->name('interactions.show');
            Route::patch('/messages/{id}/status', 'updateStatus')->name('interactions.status');
            Route::post('/messages/send', 'store')->name('interactions.store');
            Route::delete('/messages/{id}', 'destroy')->name('interactions.destroy');
            Route::get('/chat/{seekerId}', 'chat')->name('interactions.chat');
        });

        Route::get('/requests', [MessageRequestController::class, 'index'])->name('requests.index');
        Route::patch('/requests/{id}/{status}', [MessageRequestController::class, 'update'])->name('requests.update');

    });
});