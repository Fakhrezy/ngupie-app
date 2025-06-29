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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.check' => \App\Http\Middleware\CheckAuth::class,
            'cashier.access' => \App\Http\Middleware\CheckCashierAccess::class,
            'barista.access' => \App\Http\Middleware\CheckBaristaAccess::class,
            'admin.access' => \App\Http\Middleware\CheckAdminAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
