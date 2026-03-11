<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebApp\AuthController;
use App\Http\Controllers\WebApp\ForumReplyController;
use App\Http\Controllers\WebApp\LikeController;
use App\Http\Controllers\WebApp\PageController;
use App\Http\Controllers\WebApp\StemController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes (Login / Register)
|--------------------------------------------------------------------------
*/

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/vault/download/{id}', [StemController::class, 'download'])->name('webapp.stems.download');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/vault/create', [PageController::class, 'createThread'])->name('webapp.forum.create');
    Route::post('/vault/store', [PageController::class, 'storeThread'])->name('webapp.forum.store');

    // Profile & Studio Management
    Route::prefix('vault')->name('webapp.')->group(function () {
        Route::get('/profile/edit', [PageController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [PageController::class, 'updateProfile'])->name('profile.update');
    });

    Route::post('/forum/thread/{thread}/reply', [ForumReplyController::class, 'store'])
        ->name('webapp.forum.reply')
        ->middleware('auth');

    Route::put('/forum/reply/{id}', [ForumReplyController::class, 'update'])->name('webapp.forum.reply.update');
    Route::delete('/forum/reply/{id}', [ForumReplyController::class, 'destroy'])->name('webapp.forum.reply.delete');
});

Route::middleware('auth')->group(function () {
    Route::post('/stems/{id}/like', [StemController::class, 'toggleLike'])->name('webapp.stems.like');
    Route::get('/stems/{id}/download', [StemController::class, 'download'])->name('webapp.stems.download');
    Route::post('/toggle-like', [LikeController::class, 'toggle'])->name('webapp.like.toggle');
});

Route::prefix('stems')->name('webapp.')->group(function () {
    Route::get('/', [StemController::class, 'index'])->name('streams');
    Route::get('/{id}', [StemController::class, 'show'])->name('stems.show');
    Route::get('/{id}/download', [StemController::class, 'download'])->name('stems.download');
    Route::post('/{id}/like', [StemController::class, 'toggleLike'])->name('stems.like');
});

/*
|--------------------------------------------------------------------------
| Shared Vault Features (Discovery)
|--------------------------------------------------------------------------
*/
Route::prefix('vault')->name('webapp.')->group(function () {
    Route::get('/trending', [PageController::class, 'trending'])->name('trending');
    Route::get('/streams', [PageController::class, 'streams'])->name('streams');
    Route::get('/forum/{id}', [PageController::class, 'showForum'])->name('forum.show');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
});
