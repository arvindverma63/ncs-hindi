<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

if (!function_exists('mb_split')) {
    function mb_split($pattern, $string, $limit = -1) {
        return preg_split('/' . str_replace('/', '\/', $pattern) . '/u', $string, $limit);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                if ($request->user()->role === 'admin') {
                    return route('admin.dashboard', absolute: false);
                }

                return route('dashboard', absolute: false);
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function ($response) {
            if ($response->getStatusCode() === 419) {
                return redirect()->back()->withInput()->with('error', 'Your session expired. Please resubmit the form.');
            }
            return $response;
        });
    })->create();







