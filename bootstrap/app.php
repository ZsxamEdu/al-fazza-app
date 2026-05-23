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
        
        // 1. Kenalkan 'role' ke Laravel agar error-nya hilang
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 2. Ini kode pengecualian CSRF Midtrans yang tadi
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();