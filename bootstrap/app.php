<?php

use App\Helpers\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::error('Validation failed', 422, $e->errors());
        });

        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $e) {
            return ApiResponse::error('Resource not found', 404);
        });

        $exceptions->render(function (AuthenticationException|AuthorizationException $e) {
            return ApiResponse::error('Unauthorized access', 403);
        });

        // Catch-all for any other unhandled exceptions
        // $exceptions->render(function (Throwable $e) {
        //     return ApiResponse::error('Internal Server error', 500);
        // });
    })->create();
