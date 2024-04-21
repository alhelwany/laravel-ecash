<?php

namespace Alhelwany\LaravelEcash\Utilities;

use Alhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Models\EcashPayment;

class PaymentModelUtility
{
    /**
     * Creates EcashPayment Model from ExtendedPaymentDataObject
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return EcashPayment
     */
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

    /**
     * Updates verification_code in the EcashPayment Model
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return void
     */
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

    /**
     * Updates checkout_url in the EcashPayment Model
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return void
     */
    public function updateCheckoutUrl(ExtendedPaymentDataObject $extendedPaymentDataObject): void
    {
        EcashPayment::query()
            ->where([
                'id' => $extendedPaymentDataObject->getId()
            ])
            ->update([
                'checkout_url' => $extendedPaymentDataObject->getCheckoutUrl()
            ]);
    }
}
