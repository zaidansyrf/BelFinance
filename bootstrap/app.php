<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware cek role (app/Http/kernel.php tidak dipakai di Laravel 11)
        $middleware->alias([
            'role' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // User yang sudah login dan membuka halaman guest diarahkan sesuai role
        $middleware->redirectUsersTo(function () {
            return auth()->user()?->role === 'owner'
                ? route('owner-beranda')
                : route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
