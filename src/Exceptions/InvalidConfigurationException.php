<?php

namespace Alhelwany\LaravelEcash\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    public function __construct()
    {
        $message = "Config variables have not been set properly. Make sure your .env file has the variables mentioned in the readme and run config:clear";
        parent::__construct($message);
    }
}
