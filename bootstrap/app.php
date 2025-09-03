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
        // Middleware alias admin sederhana
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'maintenance.check' => \App\Http\Middleware\MaintenanceMode::class,
            'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);
        
        // Add timezone middleware globally
        $middleware->append(\App\Http\Middleware\SetTimezone::class);
        // Maintenance middleware tidak dipasang global agar urutan session & auth tetap benar
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
