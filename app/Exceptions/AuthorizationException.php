<?php

namespace App\Exceptions;

use Exception;

class AuthorizationException extends Exception
{
    public function __construct(string $message = "You do not have permission to perform this action", int $statusCode = 403)
    {
        parent::__construct($message, $statusCode);
    }
}
