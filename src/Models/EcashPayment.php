<?php

namespace Alhelwany\LaravelEcash\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Enums\Currency;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;

/**
 * @property string $id
 * @property float $amount
 * @property CheckoutType $checkout_type
 * @property Currency $currency
 * @property PaymentStatus $status
 * @property string $verification_code
 * @property string $transaction_no
 * @property string $message                                                                                                                                                                                                                              $name
 * @property string $checkout_url
 * 
 */

class EcashPayment extends Model
{
    use HasUuids;

    public $fillable = [
        'amount',
        'checkout_type',
        'currency',
        'status',
        'verification_code',
        'transaction_no',
        'message',
        'checkout_url'
    ];

    protected $casts = [
        'checkout_type' => CheckoutType::class,
        'currency' => Currency::class,
        'status' => PaymentStatus::class,
    ];

    public function getId(): string
    {
        return $this->id;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verification_code;
    }

    public function getCheckoutUrl(): string
    {
        return $this->checkout_url;
    }
}
