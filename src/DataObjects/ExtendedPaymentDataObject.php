<?php

namespace MhdGhaithAlhelwany\LaravelEcash\DataObjects;

class ExtendedPaymentDataObject extends PaymentDataObject
{
    private $id;
    private $verificationCode;


    public function __construct(PaymentDataObject $paymentDataObject)
    {
        $this->checkoutType = $paymentDataObject->getCheckoutType();
        $this->amount = $paymentDataObject->getAmount();
        $this->lang = $paymentDataObject->getLang();
        $this->currency = $paymentDataObject->getCurrency();
        $this->redirectUrl = $paymentDataObject->getRedirectUrl();
    }

    public function setVerificationCode(string $verificationCode): void
    {
        $this->verificationCode = $verificationCode;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
