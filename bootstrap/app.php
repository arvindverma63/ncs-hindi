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
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                // Ensure we return a STRING path
                if ($request->user()->role === 'admin') {
                    return '/admin/dashboard';
                }
                return '/dashboard';
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
