<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use MhdGhaithAlhelwany\LaravelEcash\Http\Rules\CallbackTokenValid;

class CallbackRequest extends FormRequest
{

    public function rules()
    {
        return [
            'IsSuccess' => ['required', 'boolean'],
            'Message' => ['required'],
            'OrderRef' => ['required'],
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

    public function getAmount(): float
    {
        return $this->input("Amount");
    }

    public function getMessage(): string
    {
        return $this->input("Message");
    }
}
