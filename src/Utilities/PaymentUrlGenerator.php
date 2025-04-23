<?php

namespace Alhelwany\LaravelEcash\Utilities;

use Alhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;

class PaymentUrlGenerator
{
    private $gatewayUrl;

    private $terminalKey;

    private $merchantId;

    private ArrayToUrl $arrayToUrl;

    /**
     * @param string $gatewayUrl
     * @param string $terminalKey
     * @param string $merchantId
     * @param ArrayToUrl $arrayToUrl
     */
    public function __construct(string $gatewayUrl, string $terminalKey, string $merchantId, ArrayToUrl $arrayToUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
        $this->terminalKey = $terminalKey;
        $this->merchantId = $merchantId;
        $this->arrayToUrl = $arrayToUrl;
    }

    /**
     * Generates Payment Url to be redirected to
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return string
     */
    public function generateUrl(ExtendedPaymentDataObject $extendedPaymentDataObject): string
    {
        $redirectUrl = is_null($extendedPaymentDataObject->getRedirectUrl()) ? config('app.url') : $extendedPaymentDataObject->getRedirectUrl();
        return $this->arrayToUrl->generate($this->gatewayUrl . '/checkout/' . $extendedPaymentDataObject->getCheckoutType()->value, [
            'tk' => $this->terminalKey,
            'mid' => $this->merchantId,
            'vc' => $extendedPaymentDataObject->getVerificationCode(),
            'c' => $extendedPaymentDataObject->getCurrency()->value,
            'a' => $extendedPaymentDataObject->getAmount(),
            'lang' => $extendedPaymentDataObject->getLang()->value,
            'or' => $extendedPaymentDataObject->getId(),
            'ru' =>
                route('ecash.redirect', [
                    'paymentId' => $extendedPaymentDataObject->getId(),
                    'token' => $extendedPaymentDataObject->getVerificationCode(),
                    'redirect_url' => $redirectUrl
                ])
            ,
            'cu' => route('ecash.callback'),
        ]);
    }
}
