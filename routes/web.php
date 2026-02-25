<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BodyPartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SeekerController;
use App\Http\Controllers\Admin\WorkoutLevelController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InteractionController;
use App\Http\Controllers\Admin\MediaGalleryController;
use App\Http\Controllers\Admin\MessageRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


Route::get('/SeekerLayout',function(){
    Artisan::call('make:component SeekerLayout');
});


Route::get('setup-notification',function(){
    Artisan::call('notifications:table');
});

Route::get('/export',function(){
    Artisan::call('make:import SeekersImport --model=SeekerProfile');
});

Route::get('/cc', function () {

    Artisan::call('optimize:clear');
    echo "Cache cleared..";
});
Route::get('/migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration executed successfully';
});

Route::middleware('auth','role:0,1')->group(function () {

    // 1. Main Dashboard (Redirects here after login)


    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->as('admin.')->group(function () {

    Route::get('/connection-logs', [MessageRequestController::class, 'index'])->name('requests.index');
    Route::delete('/connection-logs/{id}', [MessageRequestController::class, 'destroy'])->name('requests.destroy');
    Route::get('/message-user/{id}', [InteractionController::class, 'createDirectMessage'])->name('messages.create');
    Route::post('/message-user', [InteractionController::class, 'storeDirectMessage'])->name('messages.store');

    Route::controller(MediaGalleryController::class)->group(function () {
        Route::get('/media', 'index')->name('media.index');
        Route::post('/media/upload', 'upload')->name('media.upload');
        
        Route::delete('/media/{id}', 'destroy')->name('media.destroy');
    });

        Route::resource('blogs', BlogController::class);
        
        Route::post('blogs/update-status', [BlogController::class, 'updateStatus'])->name('blogs.update-status');
        
        Route::get('blogs/export/excel', [BlogController::class, 'exportExcel'])->name('blogs.export.excel');
        Route::get('blogs/export/pdf', [BlogController::class, 'exportPdf'])->name('blogs.export.pdf');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::prefix('settings')->as('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::get('/payment-gateway', [SettingController::class, 'paymentGateway'])->name('payment-gateway');
            Route::put('/payment-gateway', [SettingController::class, 'updatePaymentGateway'])->name('update-payment-gateway');
            Route::get('/sms-gateway', [SettingController::class, 'smsGateway'])->name('sms-gateway');
            Route::put('/sms-gateway', [SettingController::class, 'updateSmsGateway'])->name('update-sms-gateway');
            Route::get('/mail-config', [SettingController::class, 'mailConfig'])->name('mail-config');
            Route::put('/mail-config', [SettingController::class, 'updateMailConfig'])->name('update-mail-config');
            Route::get('/social-links', [SettingController::class, 'socialLinks'])->name('social-links');
            Route::put('/social-links', [SettingController::class, 'updateSocialLinks'])->name('update-social-links');
            Route::get('/page-setting', [SettingController::class, 'pageSetting'])->name('page-setting');
            Route::put('/page-setting', [SettingController::class, 'updatePageSetting'])->name('update-page-setting');
        });

        Route::resource('users', UserController::class);
        Route::post('users/update-status', [UserController::class, 'updateStatus'])
            ->name('users.update-status');

            
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('seekers', SeekerController::class);
        Route::get('seekers/export/excel', [SeekerController::class, 'exportExcel'])->name('seekers.export.excel');
        Route::get('seekers/export/pdf', [SeekerController::class, 'exportPdf'])->name('seekers.export.pdf');
        Route::post('seekers/import', [SeekerController::class, 'importExcel'])->name('seekers.import');
        Route::resource('coaches', CoachController::class);
        Route::post('coaches/import', [CoachController::class, 'importExcel'])->name('coaches.import');
        // Coach Exports
        Route::get('coaches/export/excel', [CoachController::class, 'exportExcel'])->name('coaches.export.excel');
        Route::get('coaches/export/pdf', [CoachController::class, 'exportPdf'])->name('coaches.export.pdf');
        Route::resource('categories', CategoryController::class);
    
        Route::patch('/coaches/{id}/status', [CoachController::class, 'updateStatus'])
        ->name('coaches.update-status');

            // Page Routes
        Route::controller(PageController::class)->group(function () {
            // List all pages
            Route::get('pages', 'index')->name('pages.index');

            // Edit & Update specific page types
            Route::get('pages/{type}/edit', 'edit')->name('pages.edit');
            Route::put('pages/{type}', 'update')->name('pages.update');
        });

        Route::get('/notifications/read', function () {
                auth()->user()->unreadNotifications->markAsRead();
                return back();
            })->name('notifications.markAsRead');
    });
});

require __DIR__ . '/auth.php';
require __DIR__ . '/coach.php';
require __DIR__ . '/seeker.php';