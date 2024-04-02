<?php

namespace GeorgeTheNerd\LaravelEcash\DataObjects;

use GeorgeTheNerd\LaravelEcash\Enums\CheckoutType;
use GeorgeTheNerd\LaravelEcash\Enums\Currency;
use GeorgeTheNerd\LaravelEcash\Enums\Lang;

class PaymentDataObject
{

    private CheckoutType $checkoutType;
    private float $amount;
    private Lang $lang = Lang::AR;
    private Currency $currency = Currency::SYP;
    private string|null $redirectUrl = null;

    public function __construct(CheckoutType $checkoutType, float $amount)
    {
        $this->checkoutType = $checkoutType;
        $this->amount = $amount;
    }

    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function setLang(Lang $lang): void
    {
        $this->lang = $lang;
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function getCheckoutType(): CheckoutType
    {
        return $this->checkoutType;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getRedirectUrl(): string|null
    {
        return $this->redirectUrl;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }
}
