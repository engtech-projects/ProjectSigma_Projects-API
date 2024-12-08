<?php

namespace App\Exceptions;

use Exception;

class ThrottleRequestsException extends Exception
{
    public function __construct(string $message = "Too many requests", int $statusCode = 429)
    {
        parent::__construct($message, $statusCode);
    }
}
