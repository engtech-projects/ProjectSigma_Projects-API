<?php

namespace App\Exceptions;

use Exception;

class DBTransactionException extends Exception
{
    protected $code;
    public function __construct(?string $message = "Transaction failed. Please try again.", ?int $code = 0, Exception $previous = null)
    {
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }
    public function getStatusCode(): int
    {
        return $this->code;
    }
}
