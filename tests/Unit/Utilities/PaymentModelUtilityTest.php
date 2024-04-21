<?php

use Alhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Models\EcashPayment;
use Alhelwany\LaravelEcash\Utilities\PaymentModelUtility;

$paymentModelUtility = new PaymentModelUtility;


it('can create models', function () use ($paymentModelUtility) {
    $amount = 100.10;
    $checkoutType = CheckoutType::CARD;

    $extendedPaymentDataObject = new ExtendedPaymentDataObject(new PaymentDataObject($checkoutType, $amount));
    expect(EcashPayment::count())->toBe(0);
    $paymentModelUtility->create($extendedPaymentDataObject);
    expect(EcashPayment::count())->toBe(1);
    $model = EcashPayment::first();
    expect($model->amount)->toBe($amount);
    expect($model->checkout_type)->toBe($checkoutType);
    expect($model->verification_code)->toBe(null);
    expect($model->status)->toBe(PaymentStatus::PENDING);
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
    expect($model->verification_code)->toBe($verificationCode);
});
