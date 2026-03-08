<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // Register custom route files here

    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register the middleware alias
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Optional: Redirect users to their specific dashboard if they hit the default /home
        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                if ($request->user()->role === 'admin') {
                    return route('admin.dashboard');
                }
                return route('coach.dashboard');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
