<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\AutoLogout;
use App\Http\Middleware\CheckDepartment;
use App\Http\Middleware\CheckApprovedIP;
use App\Http\Middleware\FeatureAccessMiddleware;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: '/api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => CheckRole::class,
            'check.approved.ip' => CheckApprovedIP::class,
            'feature.access' =>FeatureAccessMiddleware::class,
            'auto.logout' => AutoLogout::class,
            'department' => CheckDepartment::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
