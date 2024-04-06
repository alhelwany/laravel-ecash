<?php

namespace Organon\LaravelEcash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedirectRequest extends FormRequest
{

    public function rules()
    {
        return [
            'paymentId' => ['required', 'numeric'],
            'redirect_url' => ['required', 'url'],
            'token' => ['required', 'string']
        ];
    }

    public function getRedirectUrl(): string
    {
        return $this->input('redirect_url');
    }

    public function getToken(): string
    {
        return $this->input('token');
    }

    public function getPaymentId(): int
    {
        return $this->input('paymentId');
    }
}
