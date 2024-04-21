<?php

namespace Alhelwany\LaravelEcash;

use Alhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Exceptions\InvalidAmountException;
use Alhelwany\LaravelEcash\Exceptions\InvalidConfigurationException;
use Alhelwany\LaravelEcash\Models\EcashPayment;
use Alhelwany\LaravelEcash\Utilities\ArrayToUrl;
use Alhelwany\LaravelEcash\Utilities\CallbackTokenVerifier;
use Alhelwany\LaravelEcash\Utilities\PaymentModelUtility;
use Alhelwany\LaravelEcash\Utilities\PaymentUrlGenerator;
use Alhelwany\LaravelEcash\Utilities\UrlEncoder;
use Alhelwany\LaravelEcash\Utilities\VerificationCodeGenerator;


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
        $this->paymentModelUtility = new PaymentModelUtility;
        $this->callbackTokenVerifier = new CallbackTokenVerifier($merchantId, $merchantSecret);
    }

    /**
     * Checks if string is empty
     *
     * @param string|null $value
     * @return boolean
     */
    private static function isEmpty(string|null $value): bool
    {
        return is_null($value) || $value == "";
    }

    /**
     * Returns instance of self using the configs
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (
            self::isEmpty(config('ecash.gatewayUrl')) ||
            self::isEmpty(config('ecash.terminalKey')) ||
            self::isEmpty(config('ecash.merchantId')) ||
            self::isEmpty(config('ecash.merchantSecret'))
        )
            throw new InvalidConfigurationException;

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
     * @return void
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
     * @throws InvalidAmountException
     * @return EcashPayment
     */
    public function checkout(PaymentDataObject $paymentDataObject): EcashPayment
    {
        if ($paymentDataObject->getAmount() <= 0)
            throw new InvalidAmountException;

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
     * @param string $amount
     * @param string $orderRef
     * @return boolean
     */
    public function verifyCallbackToken(string $token, string $transactionNo, string $amount, string $orderRef): bool
    {
        return $this->callbackTokenVerifier->verify($token, $transactionNo, $amount, $orderRef);
    }
}
