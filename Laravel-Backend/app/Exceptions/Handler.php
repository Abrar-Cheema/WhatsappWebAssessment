<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return  response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => 'URL not found',
                    'payload' => []
                ], 404);
            }
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return  response()->json([
                    'success' => false,
                    'code' => 405,
                    'message' => 'Method not allowed',
                    'payload' => []
                ], 405);
            }
        });
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') && $e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Unauthenticated',
                    'payload' => []
                ], 401);
            }

            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'payload' => []
                ], 500);
            }
        });
        // $this->renderable(function (Throwable $e) {

        // });
    }
}
