<?php

namespace Alhelwany\LaravelEcash\Exceptions;

use Exception;

class PaymentNotPaidException extends Exception
{
    public function __construct()
    {
        $message = "The payment has not been paid yet, it cannot be refunded.";
        parent::__construct($message);
    }
}
