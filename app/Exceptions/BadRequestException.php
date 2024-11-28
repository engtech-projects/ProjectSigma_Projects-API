<?php

namespace App\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    public function __construct(string $message = "Bad request", int $statusCode = 400)
    {
        parent::__construct($message, $statusCode);
    }
}
