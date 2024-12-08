<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    protected $message;
    protected $statusCode;
    
    public function __construct(string $message = "Resource not found", int $statusCode = 404)
    {
        parent::__construct($message, $statusCode);
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
