<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Utilities;

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Enums\PaymentStatus;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;

class PaymentModelUtility
{
    public function create(ExtendedPaymentDataObject $extendedPaymentDataObject): EcashPayment
    {
        $model = EcashPayment::create([
            'amount' => $extendedPaymentDataObject->getAmount(),
            'checkout_type' => $extendedPaymentDataObject->getCheckoutType(),
            'currency' => $extendedPaymentDataObject->getCurrency(),
            'status' => PaymentStatus::PENDING
        ]);
        $extendedPaymentDataObject->setId($model->getId());
        return $model;
    }

    public function updateVerificationCode(ExtendedPaymentDataObject $extendedPaymentDataObject): void
    {
        EcashPayment::query()
            ->where([
                'id' => $extendedPaymentDataObject->getId()
            ])
            ->update([
                'verification_code' => $extendedPaymentDataObject->getVerificationCode()
            ]);
    }
}
