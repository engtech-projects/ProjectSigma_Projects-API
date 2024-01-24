<?php

namespace App\Exceptions;

use Exception;

class NoRecordFoundException extends Exception
{
    protected $code;
    public function __construct($message = "No record found.", $code = 0, Exception $previous = null)
    {
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }
    public function getStatusCode(): int
    {
        return $this->code;
    }
}
