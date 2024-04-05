<?php

namespace MhdGhaithAlhelwany\LaravelEcash\DataObjects;

use MhdGhaithAlhelwany\LaravelEcash\Enums\CheckoutType;
use MhdGhaithAlhelwany\LaravelEcash\Enums\Currency;
use MhdGhaithAlhelwany\LaravelEcash\Enums\Lang;

class PaymentDataObject
{
    protected CheckoutType $checkoutType;

    protected float $amount;

    protected Lang $lang = Lang::AR;

    protected Currency $currency = Currency::SYP;

    protected ?string $redirectUrl = null;

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

    public function getRedirectUrl(): ?string
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
