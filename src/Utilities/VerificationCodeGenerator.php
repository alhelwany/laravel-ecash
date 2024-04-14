<?php

namespace Organon\LaravelEcash\Utilities;

use Organon\LaravelEcash\DataObjects\ExtendedPaymentDataObject;

class VerificationCodeGenerator
{
    private string $merchantId;

    private string $merchantSecret;

    /**
     * @param string $merchantId
     * @param string $merchantSecret
     */
    public function __construct(string $merchantId, string $merchantSecret)
    {
        $this->merchantId = $merchantId;
        $this->merchantSecret = $merchantSecret;
    }

    /**
     * Generates Verification Code
     *
     * @param float $amount
     * @param string|integer $orderRef
     * @return string
     */
    public function generate(float $amount, string|int $orderRef): string
    {
        return strtoupper(md5($this->merchantId . $this->merchantSecret . $amount . $orderRef));
    }

    /**
     * Generates Verification Code from ExtendedPaymentDataObject
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return string
     */
    public function generateFromExtendedPaymentDataObject(ExtendedPaymentDataObject $extendedPaymentDataObject): string
    {
        $verificationCode = $this->generate($extendedPaymentDataObject->getAmount(), $extendedPaymentDataObject->getId());
        $extendedPaymentDataObject->setVerificationCode($verificationCode);
        return $verificationCode;
    }
}
