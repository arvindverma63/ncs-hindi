<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebApp\PageController;

Route::get('/', [PageController::class, 'index'])->name('home');

Route::prefix('vault')->name('webapp.')->group(function () {
    Route::get('/trending', [PageController::class, 'trending'])->name('trending');
    Route::get('/streams', [PageController::class, 'streams'])->name('streams');
    Route::get('/forum/{id}', [PageController::class, 'showForum'])->name('forum.show');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
});
