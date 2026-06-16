<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebApp\AuthController;
use App\Http\Controllers\WebApp\CommunityMessageController;
use App\Http\Controllers\WebApp\ForumReplyController;
use App\Http\Controllers\WebApp\LikeController;
use App\Http\Controllers\WebApp\PageController;
use App\Http\Controllers\WebApp\SearchController;
use App\Http\Controllers\WebApp\MusicController;
use App\Http\Controllers\WebApp\BugReportController;
use App\Http\Controllers\WebApp\MusicCommentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/firebase-messaging-sw.js', function () {
    $firebaseConfig = [
        'apiKey' => config('services.firebase.api_key'),
        'authDomain' => config('services.firebase.auth_domain'),
        'projectId' => config('services.firebase.project_id'),
        'storageBucket' => config('services.firebase.storage_bucket'),
        'messagingSenderId' => config('services.firebase.messaging_sender_id'),
        'appId' => config('services.firebase.app_id'),
    ];

    $firebaseConfig = array_filter($firebaseConfig);

$js = <<<JS
importScripts('https://www.gstatic.com/firebasejs/10.12.4/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.4/firebase-messaging-compat.js');

const firebaseConfig = %s;

if (firebaseConfig.projectId) {
    console.log('[NCS FCM SW] Initializing with project', firebaseConfig.projectId);
    self.addEventListener('install', function(event) {
        console.log('[NCS FCM SW] Install event');
        self.skipWaiting();
    });

    self.addEventListener('activate', function(event) {
        console.log('[NCS FCM SW] Activate event');
        event.waitUntil(self.clients.claim());
    });

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage(function(payload) {
        console.log('[NCS FCM SW] Background message received', payload);
        const title = payload?.notification?.title || 'New music update';
        const options = {
            body: payload?.notification?.body || 'A new release is available.',
            icon: '/assets/images/favicon.ico',
            data: payload?.data || {},
        };

        self.registration.showNotification(title, options);
    });
} else {
    console.warn('[NCS FCM SW] Firebase config missing');
}
JS;

    return response(sprintf($js, json_encode($firebaseConfig, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)))
        ->header('Content-Type', 'application/javascript')
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
})->name('firebase.messaging-sw');

Route::get('/sw.js', function () {
    $path = base_path('sw.js');

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/javascript',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
    ]);
})->name('ads.sw');

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

Route::prefix('community')->middleware('auth')->group(function () {
    Route::get('/chat/{channel?}', [CommunityMessageController::class, 'showChat'])
        ->name('webapp.community.chat');

    Route::get('/api/messages/{channel}', [CommunityMessageController::class, 'fetchMessages'])
        ->where('channel', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
    Route::get('/messages/{channelId}', [CommunityMessageController::class, 'index'])->name('community.message.index');
    Route::post('/messages', [CommunityMessageController::class, 'store'])->name('community.message.store');
    Route::delete('/messages/{id}', [CommunityMessageController::class, 'destroy']);
});

Route::get('/search', [SearchController::class, 'search'])->name('webapp.search');
Route::get('/search-all', [SearchController::class, 'index'])->name('webapp.search.index');
Route::get('/forum/thread/{slug}', [PageController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::post('/notifications/fcm-token', [\App\Http\Controllers\Auth\AdminAuthController::class, 'updateFcm'])
    ->name('webapp.notifications.fcm');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/vault/create', [PageController::class, 'createThread'])->name('webapp.forum.create');
    Route::post('/vault/store', [PageController::class, 'storeThread'])->name('webapp.forum.store');
    Route::post('/vault/upload-editor-image', [PageController::class, 'uploadEditorImage'])->name('webapp.upload-image');
    Route::get('/vault/report-bug', [BugReportController::class, 'create'])->name('webapp.bug-reports.create');
    Route::post('/vault/report-bug', [BugReportController::class, 'store'])->name('webapp.bug-reports.store');

    // Profile & Studio Management
    Route::prefix('vault')->name('webapp.')->group(function () {
        Route::get('/profile/edit', [PageController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [PageController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    });

    Route::post('/forum/thread/{thread}/reply', [ForumReplyController::class, 'store'])
        ->name('webapp.forum.reply')
        ->middleware('auth');

    Route::put('/forum/reply/{id}', [ForumReplyController::class, 'update'])->name('webapp.forum.reply.update');
    Route::delete('/forum/reply/{id}', [ForumReplyController::class, 'destroy'])->name('webapp.forum.reply.delete');
});

Route::middleware('auth')->group(function () {
    Route::post('/music/{id}/like', [MusicController::class, 'toggleLike'])->name('webapp.music.like');
    Route::post('/toggle-like', [LikeController::class, 'toggle'])->name('webapp.like.toggle');
});

Route::prefix('music')->name('webapp.')->group(function () {
    Route::get('/', [MusicController::class, 'index'])->name('streams');
    Route::get('/genre/{slug}', [MusicController::class, 'genre'])->name('music.genre');
    Route::get('/{id}/download', [MusicController::class, 'download'])->name('music.download');
    Route::get('/{slug}', [MusicController::class, 'show'])->name('music.show');
    Route::post('/{id}/increment-download', [MusicController::class, 'incrementDownload'])->name('music.increment-download');
    Route::post('/{id}/like', [MusicController::class, 'toggleLike'])->name('music.like');
    // Comment routes
    Route::post('/{id}/comments', [MusicCommentController::class, 'store'])->name('music.comments.store');
    Route::post('/comments/{commentId}/react', [MusicCommentController::class, 'react'])->name('music.comments.react');
    Route::post('/comments/upload-image', [MusicCommentController::class, 'uploadImage'])->name('music.comments.upload-image');
});

/*
|--------------------------------------------------------------------------
| Shared Vault Features (Discovery)
|--------------------------------------------------------------------------
*/
Route::prefix('vault')->name('webapp.')->group(function () {
    Route::get('/trending', [PageController::class, 'trending'])->name('trending');
    Route::get('/streams', [PageController::class, 'streams'])->name('streams');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
    Route::get('/forum/{slug}', [PageController::class, 'show'])->name('forum.show');
    Route::get('/game', [PageController::class, 'gamesList'])->name('game');
    Route::get('/game/rhythm-tapper', [PageController::class, 'game'])->name('game.rhythm-tapper');
    Route::get('/game/2048', [PageController::class, 'game2048'])->name('game.2048');
    Route::get('/game/neon-serpent', [PageController::class, 'gameNeonSerpent'])->name('game.neon-serpent');
    Route::post('/game/award-credits', [PageController::class, 'awardCredits'])->name('game.award-credits');
});






