<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Models;

use Illuminate\Database\Eloquent\Model;
use MhdGhaithAlhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Enums\CheckoutType;
use MhdGhaithAlhelwany\LaravelEcash\Enums\Currency;
use MhdGhaithAlhelwany\LaravelEcash\Enums\PaymentStatus;

class EcashPayment extends Model
{
    public $fillable = [
        'amount',
        'checkout_type',
        'currency',
        'status'
    ];

    protected $casts = [
        'checkout_type' => CheckoutType::class,
        'currency' => Currency::class,
        'status' => PaymentStatus::class,
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

    public function updateVerificationCode(string $verificationCode): self
    {
        $this->update(['verification_code' => $verificationCode]);
        return $this;
    }
}