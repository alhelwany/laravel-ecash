<?php

namespace Organon\LaravelEcash\Utilities;

use Organon\LaravelEcash\DataObjects\ExtendedPaymentDataObject;

class PaymentUrlGenerator
{
    private $gatewayUrl;

    private $terminalKey;

    private $merchantId;

    private ArrayToUrl $arrayToUrl;

    private UrlEncoder $urlEncoder;

    /**
     * @param string $gatewayUrl
     * @param string $terminalKey
     * @param string $merchantId
     * @param ArrayToUrl $arrayToUrl
     * @param UrlEncoder $urlEncoder
     */
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
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return string
     */
    public function generateUrl(ExtendedPaymentDataObject $extendedPaymentDataObject): string
    {
        $redirectUrl = is_null($extendedPaymentDataObject->getRedirectUrl()) ? config('app.url') : $extendedPaymentDataObject->getRedirectUrl();
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
            $this->urlEncoder->encode(
                route('ecash.redirect', [
                    'paymentId' => $extendedPaymentDataObject->getId(),
                    'token' => $extendedPaymentDataObject->getVerificationCode(),
                    'redirect_url' => $redirectUrl
                ])
            ),
            $this->urlEncoder->encode(route('ecash.callback')),
        ]);
    }
}
