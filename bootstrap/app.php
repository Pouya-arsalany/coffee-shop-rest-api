<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\AuthMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
        ]);

        $middleware->alias([
            'login_status' => \App\Http\Middleware\AuthMiddleware::class,
            'user_role' => \App\Http\Middleware\UserMiddleware::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
