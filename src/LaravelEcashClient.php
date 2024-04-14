<?php

namespace Organon\LaravelEcash;

use Organon\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Organon\LaravelEcash\DataObjects\PaymentDataObject;
use Organon\LaravelEcash\Models\EcashPayment;
use Organon\LaravelEcash\Utilities\ArrayToUrl;
use Organon\LaravelEcash\Utilities\CallbackTokenVerifier;
use Organon\LaravelEcash\Utilities\PaymentModelUtility;
use Organon\LaravelEcash\Utilities\PaymentUrlGenerator;
use Organon\LaravelEcash\Utilities\UrlEncoder;
use Organon\LaravelEcash\Utilities\VerificationCodeGenerator;


class LaravelEcashClient
{
    private PaymentUrlGenerator $paymentUrlGenerator;
    private VerificationCodeGenerator $verificationCodeGenerator;
    private PaymentModelUtility $paymentModelUtility;
    private CallbackTokenVerifier $callbackTokenVerifier;

    /**
     * @param string $gatewayUrl
     * @param string $terminalKey
     * @param string $merchantId
     * @param string $merchantSecret
     */
    public function __construct(string $gatewayUrl, string $terminalKey, string $merchantId, string $merchantSecret)
    {
        $this->paymentUrlGenerator = new PaymentUrlGenerator(
            $gatewayUrl,
            $terminalKey,
            $merchantId,
            new ArrayToUrl,
            new UrlEncoder
        );
        $this->verificationCodeGenerator = new VerificationCodeGenerator($merchantId, $merchantSecret);
        $this->paymentModelUtility = new PaymentModelUtility($this->verificationCodeGenerator);
        $this->callbackTokenVerifier = new CallbackTokenVerifier($merchantId, $merchantSecret);
    }

    /**
     * Returns instance of self using the configs
     *
     * @return self
     */
    public static function getInstance(): self
    {
        return new self(
            config('ecash.gatewayUrl'),
            config('ecash.terminalKey'),
            config('ecash.merchantId'),
            config('ecash.merchantSecret')
        );
    }


    /**
     * Creates EcashPayment Model with the verification code - updates the verification code in ExtendedPaymentDataObject
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return EcashPayment
     */
    private function createModel(ExtendedPaymentDataObject $extendedPaymentDataObject): EcashPayment
    {
        $model = $this->paymentModelUtility->create($extendedPaymentDataObject);
        $this->verificationCodeGenerator->generateFromExtendedPaymentDataObject($extendedPaymentDataObject);
        $this->paymentModelUtility->updateVerificationCode($extendedPaymentDataObject);
        $model->refresh();
        return $model;
    }

    /**
     * Generates the checkout URL from ExtendedPaymentDataObject
     *
     * @param ExtendedPaymentDataObject $extendedPaymentDataObject
     * @return string
     */
    private function generateUrl(ExtendedPaymentDataObject $extendedPaymentDataObject): void
    {
        $extendedPaymentDataObject->setCheckoutUrl($this->paymentUrlGenerator->generateUrl($extendedPaymentDataObject));
    }

    private function addGeneratedUrlToModel($extendedPaymentDataObject): void
    {
        $this->paymentModelUtility->updateCheckoutUrl($extendedPaymentDataObject);
    }

    /**
     * Starts the transaction process
     * Creates EcashPayment model and generates the checkout url
     *
     * @param PaymentDataObject $paymentDataObject
     * @return EcashPayment
     */
    public function checkout(PaymentDataObject $paymentDataObject): EcashPayment
    {
        $extendedPaymentDataObject = new ExtendedPaymentDataObject($paymentDataObject);
        $model = $this->createModel($extendedPaymentDataObject);
        $this->generateUrl($extendedPaymentDataObject);
        $this->addGeneratedUrlToModel($extendedPaymentDataObject);
        $model->refresh();

        return $model;
    }

    /**
     * Verifies Token sent from Ecash
     *
     * @param string $token
     * @param string $transactionNo
     * @param float $amount
     * @param integer $orderRef
     * @return boolean
     */
    public function verifyCallbackToken(string $token, string $transactionNo, string $amount, int $orderRef): bool
    {
        return $this->callbackTokenVerifier->verify($token, $transactionNo, $amount, $orderRef);
    }
}
