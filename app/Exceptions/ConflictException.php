<?php

namespace App\Exceptions;

use Exception;

class ConflictException extends Exception
{
    public function __construct(string $message = 'Conflict occurred', int $statusCode = 409)
    {
        parent::__construct($message, $statusCode);
    }
}
