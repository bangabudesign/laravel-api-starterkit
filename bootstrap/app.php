<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle Authentication Exception
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated.'
            ], 401);
        });

        // Handle Validation Exception
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        });

        // Handle Route Not Found Exception
        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            // Check if the exception message contains anything related to login
            if (str_contains($e->getMessage(), 'login')) {
                return response()->json([
                        'status' => 401,
                        'message' => 'Unauthorized'
                    ], 401);
            }
            
            return response()->json([
                    'status' => 404,
                    'message' => 'Endpoint not found.'
                ], 404
            );
        });
    })->create();
