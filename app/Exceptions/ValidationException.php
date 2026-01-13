<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $message;

    protected $statusCode;

    public function __construct($message = 'Validation failed', int $statusCode = 422)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request)
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], $this->statusCode);
    }
}
