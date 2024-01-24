<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use App\Exceptions\ResourceNotFoundException;

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
        $this->renderable(function (Exception $e, Request $request) {
            if ($request->wantsJson()) {
                return $this->handleApiExceptions($request, $e);
            }
            return abort(500, $e->getMessage());
        });
    }

    public function handleApiExceptions(Request $request, Exception $e)
    {
        $response = null;
        if ($e instanceof ResourceNotFoundException) {
            $response = new JsonResponse(['message' => $e->getMessage()], 404);
        }
        if ($e instanceof NotFoundHttpException) {
            $response = new JsonResponse(['message' => $e->getMessage()], 404);
        }
        if ($e instanceof NoRecordFoundException) {
            $response = new JsonResponse(['message' => $e->getMessage()], 422);
        }
        return $response;
    }

}
