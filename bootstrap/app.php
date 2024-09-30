<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Router;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        function (Router $router) {
            $router->middleware('web')
                ->group(base_path('routes/web.php'));

            $router->middleware(['web', 'role:user','auth', 'verified'])
                ->name('user.')
                ->prefix('user')
                ->group(base_path('routes/user.php'));

            $router->middleware(['web', 'role:admin','auth', 'verified'])
                ->name('admin.')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
        // web: [
        //     __DIR__ . '/../routes/web.php',
        //     __DIR__ . '/../routes/company.php',
        //     __DIR__ . '/../routes/admin.php',
        // ],
        commands: __DIR__ . '/../routes/console.php',
        // health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
