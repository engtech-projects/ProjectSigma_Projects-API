<?php

namespace App\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    public function __construct(string $message = "Authentication failed", int $statusCode = 401)
    {
        parent::__construct($message, $statusCode);
    }
}

