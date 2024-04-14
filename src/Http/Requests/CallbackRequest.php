<?php

namespace Organon\LaravelEcash\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Organon\LaravelEcash\Http\Rules\CallbackTokenValid;

class CallbackRequest extends FormRequest
{

    /**
     * Defines validation rules
     *
     * @return array
     */
    public function rules(): array
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

    /**
     * @return boolean
     */
    public function getIsSuccess(): bool
    {
        return $this->input('IsSuccess');
    }

    /**
     * @return int
     */
    public function getOrderRef(): int
    {
        return $this->input('OrderRef');
    }

    /**
     * @return string
     */
    public function getTransactionNo(): string
    {
        return $this->input("TransactionNo");
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->input('Token');
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->input("Amount");
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->input("Message");
    }
}
