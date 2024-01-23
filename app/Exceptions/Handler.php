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
        });
    }

    public function handleApiExceptions(Request $request, Throwable $e)
    {
        $message = $e->getMessage();
        $code = 500;
        if ($e instanceof NotFoundHttpException) {
            $message = $this->handleResourceNotFoundMessage($request);
        }
        return new JsonResponse(['message' => $message], $code);
    }
    public function handleResourceNotFoundMessage(Request $request)
    {
        $message = null;
        if ($request->is('api/projects/*')) {
            $message = 'Something went wrong. Project not found.';
        }
        return $message;
    }

}
