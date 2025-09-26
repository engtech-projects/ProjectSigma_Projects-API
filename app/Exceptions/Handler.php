<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Custom
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

        if ($e instanceof NotFoundHttpException) {
            $response = new JsonResponse(['success' => false, 'message' => 'Resource not found.'], JsonResponse::HTTP_NOT_FOUND);
        }
        if ($e instanceof DBTransactionException) {
            $response = new JsonResponse(['success' => false, 'message' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($e instanceof ResourceNotFound) {
            $response = new JsonResponse(['success' => false, 'message' => $e->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $response;
    }
}
