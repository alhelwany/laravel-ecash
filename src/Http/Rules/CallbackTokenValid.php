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

    public function __construct(public CallbackRequest $request)
    {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (LaravelEcashClient::verifyCallbackToken($this->request->getToken(), $this->request->getAmount(), $this->request->getOrderRef())) {
            $fail('Invalid Token');
        }
    }
}
