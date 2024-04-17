<?php

namespace Organon\LaravelEcash\DataObjects;

class ExtendedPaymentDataObject extends PaymentDataObject
{
    private string $id;
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
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
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
     * @return string
     */
    public function getId(): string
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
