<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use MhdGhaithAlhelwany\LaravelEcash\Facades\LaravelEcashClient;
use MhdGhaithAlhelwany\LaravelEcash\Http\Requests\CallbackRequest;

/**
 * Verifies if the token sent by ecash is valid using verifyCallbackToken in LaravelEcashClient 
 */
class CallbackTokenValid implements ValidationRule
{

    private CallbackRequest $request;

    public function __construct(CallbackRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!LaravelEcashClient::verifyCallbackToken($value, $this->request->getTransactionNo(), $this->request->getAmount(), $this->request->getOrderRef())) {
            $fail('Invalid Token');
        }
    }
}
