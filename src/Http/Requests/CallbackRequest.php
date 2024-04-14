<?php

namespace Organon\LaravelEcash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Organon\LaravelEcash\Http\Rules\CallbackTokenValid;

class CallbackRequest extends FormRequest
{

    /**
     * Defines validation rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'IsSuccess' => ['required', 'boolean'],
            'Message' => ['nullable'],
            'OrderRef' => ['required', 'exists:ecash_payments,id'],
            'TransactionNo' => ['required'],
            'Amount' => ['required', 'numeric'],
            'Token' => [
                'required', new CallbackTokenValid($this)
            ],
        ];
    }

    public function getIsSuccess(): bool
    {
        return $this->input('IsSuccess');
    }

    public function getOrderRef(): int
    {
        return $this->input('OrderRef');
    }

    public function getTransactionNo(): string
    {
        return $this->input("TransactionNo");
    }

    public function getToken(): string
    {
        return $this->input('Token');
    }

    public function getAmount(): string
    {
        return $this->input("Amount");
    }

    public function getMessage(): string | null
    {
        return $this->input("Message");
    }
}
