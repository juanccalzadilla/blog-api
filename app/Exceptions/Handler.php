<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'Resource not found.',
                        'developer_message' => $e->getMessage(),
                    ], 404);
                } else if ($e instanceof HttpException) {
                    return response()->json([
                        'status' => 'error',
                        'code' => $e->getStatusCode(),
                        'message' => 'Resource not found.',
                        'developer_message' => $e->getMessage(),
                    ], $e->getStatusCode());
                } else {
                    return response()->json([
                        'status' => 'error',
                        'code' => 500,
                        'message' => 'Internal server error.',
                        'developer_message' => $e->getMessage(),
                    ], 500);
                }
            }
        });
    }
}
