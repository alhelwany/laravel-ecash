<?php

namespace Organon\LaravelEcash\DataObjects;

use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Enums\Currency;
use Organon\LaravelEcash\Enums\Lang;

class PaymentDataObject
{
    protected CheckoutType $checkoutType;

    protected float $amount;

    protected Lang $lang = Lang::AR;

    protected Currency $currency = Currency::SYP;

    protected ?string $redirectUrl = null;

    /**
     * @param CheckoutType $checkoutType
     * @param float $amount
     */
    public function __construct(CheckoutType $checkoutType, float $amount)
    {
        $this->checkoutType = $checkoutType;
        $this->amount = $amount;
    }

    /**
     * @return void
     */
    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return void
     */
    public function setLang(Lang $lang): void
    {
        $this->lang = $lang;
    }

    /**
     * @return void
     */
    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return CheckoutType
     */
    public function getCheckoutType(): CheckoutType
    {
        return $this->checkoutType;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }
}
