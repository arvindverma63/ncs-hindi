<?php

use App\Http\Controllers\Seeker\CoachController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Seeker\DashboardController;
use App\Http\Controllers\Seeker\MessagingController;
use App\Http\Controllers\Seeker\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authenticated Seekers Workspace
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::prefix('seeker')->as('seeker.')->group(function() {

    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::patch('/profile/update', 'update')->name('profile.update');
        });

    Route::get('/chat/{coachId}/fetch', [MessagingController::class, 'fetchMessages'])->name('messaging.fetch');
    Route::get('/find-coaches', [CoachController::class, 'index'])->name('coaches.index');
    Route::post('/connect/{id}', [CoachController::class, 'sendRequest'])->name('coaches.connect');
    Route::get('/chat/{coachId}', [MessagingController::class, 'index'])->name('messaging.chat');
    Route::post('/chat/{coachId}/send', [MessagingController::class, 'sendMessage'])->name('messaging.send');

    });
});