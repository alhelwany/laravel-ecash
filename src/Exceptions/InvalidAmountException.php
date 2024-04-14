<?php

namespace Organon\LaravelEcash\Exceptions;

use Exception;

class InvalidAmountException extends Exception
{
    public function __construct()
    {
        $message = "The checkout amount must be greater than 0";
        parent::__construct($message);
    }
}
