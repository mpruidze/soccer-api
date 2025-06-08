<?php

declare(strict_types=1);

use App\Http\Middleware\ApiLocale;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->throttleApi()
            ->append(ApiLocale::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (AuthenticationException $exception, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('auth.unauthenticated')], 401);
            }
            throw $exception;
        });
        $exceptions->renderable(function (AccessDeniedHttpException $exception, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('auth.unauthorized')], 403);
            }

            throw $exception;
        });
        $exceptions->renderable(function (NotFoundHttpException $exception, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('app.item_not_found')], 404);
            }
            throw $exception;
        });
    })->create();
