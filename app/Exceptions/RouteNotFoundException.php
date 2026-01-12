<?php

namespace App\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $message;

    protected $statusCode;

    public function __construct($message = 'The requested route does not exist.', $statusCode = 404)
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
