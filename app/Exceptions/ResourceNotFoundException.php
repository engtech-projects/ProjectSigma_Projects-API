<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public function __construct(string $message = "Resource not found", int $statusCode = 404)
    {
        parent::__construct($message, $statusCode);
    }
}
