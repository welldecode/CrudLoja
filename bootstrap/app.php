<?php

use App\Http\Middleware\Cors;
use App\Http\Middleware\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: '',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: [
            '127.0.0.1',
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
        ])->api(prepend: [
                    JsonResponse::class,
                ]);
        $middleware->validateCsrfTokens(except: [
            env('APP_URL') . '/researchjspost'
        ]);
        $middleware->append(Cors::class); // Register Cors middleware
        $middleware->append(EncryptCookies::class); // Register Cors middleware
        $middleware->append(StartSession::class); // Register Cors middleware   
        $middleware->alias([
            'api' => ThrottleRequests::class . ':api',

        ]);
        $middleware->alias([
            'api' =>
                SubstituteBindings::class,


        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        /*
         * Format not found responses
         */
        $exceptions->render(static function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api*')) {
                return response()->json([
                    'ok' => false,
                    'message' => $e->getMessage(),
                ], $e->getStatusCode(), [], JSON_UNESCAPED_SLASHES);
            }
        });

        /*
         * Format unauthorized responses
         */
        $exceptions->render(static function (AuthenticationException $e, Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse {
            if ($request->is('api*')) {
                return response()->json([
                    'ok' => false,
                    'message' => __('Unauthenticated.'),
                ], 401, [], JSON_UNESCAPED_SLASHES);
            }

            return redirect()->guest(route('login'));
        });

        /*
         * Format validation errors
         */
        $exceptions->render(static function (ValidationException $e, Request $request): \Illuminate\Http\JsonResponse {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
                'errors' => array_map(static function (string $field, array $errors): array {
                    return [
                        'path' => $field,
                        'message' => implode(' ', $errors),
                    ];
                }, array_keys($e->errors()), $e->errors()),
            ], $e->status);
        });
    })->create();