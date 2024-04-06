<?php

namespace Organon\LaravelEcash\Models;

use Illuminate\Database\Eloquent\Model;
use Organon\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Enums\Currency;
use Organon\LaravelEcash\Enums\PaymentStatus;

class EcashPayment extends Model
{
    public $fillable = [
        'amount',
        'checkout_type',
        'currency',
        'status',
        'verification_code',
        'transaction_no',
        'message'
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

    public function getVerificationCode(): ?string
    {
        return $this->verification_code;
    }
}
