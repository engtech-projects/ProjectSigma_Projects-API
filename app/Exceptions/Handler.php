<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use App\Exceptions\AuthenticationException;
use App\Exceptions\AuthorizationException;
use App\Exceptions\BadRequestException;
use App\Exceptions\ConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\DBTransactionException;
use App\Exceptions\ThrottleRequestsException;
use App\Exceptions\FileUploadException;

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
        $this->renderable(function (Exception $exception, Request $request) {

            if ($request->wantsJson()) {

                if ($exception instanceof ResourceNotFoundException) {
                    return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
                }

            }
            // Default fallback for uncaught exceptions
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
           
        });

    }
}
