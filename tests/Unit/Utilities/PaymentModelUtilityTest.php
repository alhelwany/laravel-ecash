<?php

use Organon\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Organon\LaravelEcash\DataObjects\PaymentDataObject;
use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Enums\PaymentStatus;
use Organon\LaravelEcash\Models\EcashPayment;
use Organon\LaravelEcash\Utilities\PaymentModelUtility;

$paymentModelUtility = new PaymentModelUtility;


it('can create models', function () use ($paymentModelUtility) {
    $amount = 100.10;
    $checkoutType = CheckoutType::CARD;

    $extendedPaymentDataObject = new ExtendedPaymentDataObject(new PaymentDataObject($checkoutType, $amount));
    $this->expect(EcashPayment::count())->toBe(0);
    $paymentModelUtility->create($extendedPaymentDataObject);
    $this->expect(EcashPayment::count())->toBe(1);
    $model = EcashPayment::first();
    $this->expect($model->amount)->toBe($amount);
    $this->expect($model->checkout_type)->toBe($checkoutType);
    $this->expect($model->verificationCode)->toBe(null);
    $this->expect($model->status)->toBe(PaymentStatus::PENDING);
});

it('can update verification code', function () use ($paymentModelUtility) {
    $amount = 100.10;
    $checkoutType = CheckoutType::CARD;
    $verificationCode = "123";

    $extendedPaymentDataObject = new ExtendedPaymentDataObject(new PaymentDataObject($checkoutType, $amount));
    $paymentModelUtility->create($extendedPaymentDataObject);
    $extendedPaymentDataObject->setVerificationCode($verificationCode);
    $paymentModelUtility->updateVerificationCode($extendedPaymentDataObject);

    $model = EcashPayment::first();
    $this->expect($model->verification_code)->toBe($verificationCode);
});
