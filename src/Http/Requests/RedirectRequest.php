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
            'paymentId' => ['required', 'exists:ecash_payments,id'],
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
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->input('paymentId');
    }
}
