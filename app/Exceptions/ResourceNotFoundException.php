<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{

    protected $code;
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }
    public function getStatusCode(): int
    {
        return $this->code;
    }
}
