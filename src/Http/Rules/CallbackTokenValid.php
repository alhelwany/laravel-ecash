<?php

namespace Organon\LaravelEcash\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Organon\LaravelEcash\Facades\LaravelEcashClient;
use Organon\LaravelEcash\Http\Requests\CallbackRequest;

/**
 * Verifies if the token sent by ecash is valid using verifyCallbackToken in LaravelEcashClient 
 */
class CallbackTokenValid implements ValidationRule
{

    private CallbackRequest $request;

    /**
     * @param CallbackRequest $request
     */
    public function __construct(CallbackRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!LaravelEcashClient::verifyCallbackToken($value, $this->request->getTransactionNo(), $this->request->getAmount(), $this->request->getOrderRef())) {
            $fail('Invalid Token');
        }
    }
}
