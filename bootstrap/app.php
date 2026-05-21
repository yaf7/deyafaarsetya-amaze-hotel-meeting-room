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
        // Trust all proxies for port forwarding support (like VS Code, Codespaces, etc.)
        $middleware->trustProxies(at: '*');

        // Tambahkan alias middleware custom di sini
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdminRole::class,
        ]);

        // Redirect guest sesuai dengan prefix rute (admin vs customer)
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            return route('login');
        });

        // Exclude Midtrans webhook dari CSRF protection
        $middleware->validateCsrfTokens(except: [
            'payment/notification',
            'midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 419 CSRF token expired — redirect ke halaman login
        $exceptions->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login')->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
            }
            return redirect()->route('login')->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
        });
    })->create();
