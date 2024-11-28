<?php

namespace App\Exceptions;

use Exception;

class DBTransactionException extends Exception
{
    /**
     * The status code to return.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Constructor to initialize the exception with a message and status code.
     */
    public function __construct(string $message = "Database transaction failed", int $statusCode = 500)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    /**
     * Get the status code for the exception.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}