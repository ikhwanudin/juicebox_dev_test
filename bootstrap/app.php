<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                // Check for specific model not found if needed
                $message = $e->getPrevious() instanceof ModelNotFoundException
                    ? 'The requested database record does not exist.'
                    : 'The requested resource or endpoint was not found.';

                return response()->json(['message' => $message], 404);
            }
        });

        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                if ($e instanceof ValidationException) {
                    return response()->json(['message' => 'The given data was invalid.', 'errors' => $e->errors()], 422);
                }
                if ($e instanceof AuthenticationException) {
                    return response()->json(['message' => 'Unauthenticated. Please log in.'], 401);
                }
                if ($e instanceof AccessDeniedHttpException) {
                    return response()->json(['message' => 'You do not have permission to access this resource.'], 403);
                }
                return response()->json(['message' => config('app.debug') ? $e->getMessage() : 'An unexpected server error occurred.'], 500);
            }
        });
    })->create();
