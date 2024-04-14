<?php

namespace Organon\LaravelEcash\DataObjects;

class ExtendedPaymentDataObject extends PaymentDataObject
{
    private int $id;
    private string  $verificationCode;
    private string $checkout_url;


    /**
     * @param PaymentDataObject $paymentDataObject
     */
    public function __construct(PaymentDataObject $paymentDataObject)
    {
        $this->checkoutType = $paymentDataObject->getCheckoutType();
        $this->amount = $paymentDataObject->getAmount();
        $this->lang = $paymentDataObject->getLang();
        $this->currency = $paymentDataObject->getCurrency();
        $this->redirectUrl = $paymentDataObject->getRedirectUrl();
    }

    /**
     * @param string $verificationCode
     * @return void
     */
    public function setVerificationCode(string $verificationCode): void
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * @param integer $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return void
     */
    public function setCheckoutUrl(string $checkout_url): void
    {
        $this->checkout_url = $checkout_url;
    }

    /**
     * @return string
     */
    public function getCheckoutUrl(): string
    {
        return $this->checkout_url;
    }
}
