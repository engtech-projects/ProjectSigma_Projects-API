<?php

namespace App\Exceptions;

use Exception;

class FileUploadException extends Exception
{
    public function __construct(string $message = 'File upload failed', int $statusCode = 500)
    {
        parent::__construct($message, $statusCode);
    }
}
