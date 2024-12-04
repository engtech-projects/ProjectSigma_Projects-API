<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// Custom
use App\Exceptions\AuthenticationException;
use App\Exceptions\AuthorizationException;
use App\Exceptions\BadRequestException;
use App\Exceptions\ConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\DBTransactionException;
use App\Exceptions\ThrottleRequestsException;
use App\Exceptions\FileUploadException;
use \App\Exceptions\RouteNotFoundException;

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

                if ($exception instanceof ModelNotFoundException) {
                    throw new ResourceNotFoundException();
                }

                if ($exception instanceof NotFoundHttpException) {
                    throw new RouteNotFoundException();
                }

                // if ($exception instanceof ValidationException) {
                //     throw new ValidationException();
                // }

                // if ($exception instanceof RouteNotFoundException) {
                //     return response()->json(['message' => $exception->getMessage()], $exception->getStatusCode());
                //     // return $exception->render($request);
                // }
            
            }
            
            // return parent::render($request, $exception);
            // Default fallback for uncaught exceptions
            return response()->json([
                'error' => $exception->getMessage(),
            ], 422);
        });
    }
}
