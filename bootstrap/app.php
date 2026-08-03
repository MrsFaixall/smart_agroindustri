<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\AuthenticateCustom::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
            $exceptions->render(function (Throwable $e, $request) {
                if ($request->is('api/*')) {
                    return response()->json(['message' => 'Terjadi kesalahan sistem'], 500);
                }
                return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            });
        })->create();
// Tambahkan di bootstrap/app.php