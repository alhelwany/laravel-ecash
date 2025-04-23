<?php

namespace Alhelwany\LaravelEcash\Exceptions;

use Exception;

class EcashServerErrorException extends Exception
{
    public function __construct()
    {
        $message = "Ecash server returned an error";
        parent::__construct($message);
    }
}
