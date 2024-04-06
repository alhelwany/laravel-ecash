<?php

namespace MhdGhaithAlhelwany\LaravelEcash;

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\ArrayToUrl;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\CallbackTokenVerifier;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\PaymentModelUtility;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\PaymentUrlGenerator;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\VerificationCodeGenerator;


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
    private function generateUrl(ExtendedPaymentDataObject $extendedPaymentDataObject): string
    {
        return $this->paymentUrlGenerator->generateUrl($extendedPaymentDataObject);
    }

    /**
     * Starts the transaction process
     *
     * @param PaymentDataObject $paymentDataObject
     * @return array ['EcashPayment model', 'checkoutUrl']
     */
    public function checkout(PaymentDataObject $paymentDataObject): array
    {
        $extendedPaymentDataObject = new ExtendedPaymentDataObject($paymentDataObject);
        $model = $this->createModel($extendedPaymentDataObject);
        $url = $this->generateUrl($extendedPaymentDataObject);

        return [
            'model' => $model,
            'url' => $url
        ];
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
    public function verifyCallbackToken(string $token, string $transactionNo, float $amount, int $orderRef): bool
    {
        return $this->callbackTokenVerifier->verify($token, $transactionNo, $amount, $orderRef);
    }
}
