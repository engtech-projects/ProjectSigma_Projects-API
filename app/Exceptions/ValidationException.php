<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public array $errors;

    public function __construct(string $message = "Validation failed", array $errors = [], int $statusCode = 422)
    {
        parent::__construct($message, $statusCode);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
