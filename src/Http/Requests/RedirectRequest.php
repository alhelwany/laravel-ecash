<?php

namespace Organon\LaravelEcash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedirectRequest extends FormRequest
{

    /**
     * Defines validation rules
     * @return array
     */
    public function rules(): array
    {
        return [
            'paymentId' => ['required', 'numeric', 'exists:ecash_payments,id'],
            'redirect_url' => ['required', 'url'],
            'token' => ['required', 'string']
        ];
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->input('redirect_url');
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->input('token');
    }

    /**
     * @return integer
     */
    public function getPaymentId(): int
    {
        return $this->input('paymentId');
    }
}
