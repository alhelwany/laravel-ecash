<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Utilities;

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;

class PaymentUrlGenerator
{
    private $gatewayUrl;

    private $terminalKey;

    private $merchantId;

    private ArrayToUrl $arrayToUrl;

    private UrlEncoder $urlEncoder;

    public function __construct(string $gatewayUrl, string $terminalKey, string $merchantId, ArrayToUrl $arrayToUrl, UrlEncoder $urlEncoder)
    {
        $this->gatewayUrl = $gatewayUrl;
        $this->terminalKey = $terminalKey;
        $this->merchantId = $merchantId;
        $this->arrayToUrl = $arrayToUrl;
        $this->urlEncoder = $urlEncoder;
    }

    /**
     * Generates Payment Url to be redirected to
     */
    public function generateUrl(ExtendedPaymentDataObject $extendedPaymentDataObject): string
    {
        return $this->arrayToUrl->generate($this->gatewayUrl, [
            'checkout',
            $extendedPaymentDataObject->getCheckoutType()->value,
            $this->terminalKey,
            $this->merchantId,
            $extendedPaymentDataObject->getVerificationCode(),
            $extendedPaymentDataObject->getCurrency()->value,
            $extendedPaymentDataObject->getAmount(),
            $extendedPaymentDataObject->getLang()->value,
            $extendedPaymentDataObject->getId(),
            $this->urlEncoder->encode($extendedPaymentDataObject->getRedirectUrl()),
            $this->urlEncoder->encode(route('ecash.callback')),
        ]);
    }
}
