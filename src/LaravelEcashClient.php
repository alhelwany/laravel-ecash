<?php

namespace MhdGhaithAlhelwany\LaravelEcash;

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\ArrayToUrl;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\PaymentModelUtility;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\PaymentUrlGenerator;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\VerificationCodeGenerator;

class LaravelEcashClient
{
    private PaymentUrlGenerator $paymentUrlGenerator;
    private VerificationCodeGenerator $verificationCodeGenerator;
    private PaymentModelUtility $paymentModelUtility;

    public function __construct($gatewayUrl, $terminalKey, $merchantId, $merchantSecret)
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
    }

    public static function getInstance()
    {
        return new self(
            config('ecash.gatewayUrl'),
            config('ecash.terminalKey'),
            config('ecash.merchantId'),
            config('ecash.merchantSecret')
        );
    }


    private function createModel(ExtendedPaymentDataObject $extendedPaymentDataObject): EcashPayment
    {
        $model = $this->paymentModelUtility->create($extendedPaymentDataObject);
        $this->verificationCodeGenerator->generateFromExtendedPaymentDataObject($extendedPaymentDataObject);
        $this->paymentModelUtility->updateVerificationCode($extendedPaymentDataObject);
        $model->refresh();
        return $model;
    }

    private function generateUrl(ExtendedPaymentDataObject $extendedPaymentDataObject): string
    {
        return $this->paymentUrlGenerator->generateUrl($extendedPaymentDataObject);
    }

    public function checkout(PaymentDataObject $paymentDataObject)
    {
        $extendedPaymentDataObject = new ExtendedPaymentDataObject($paymentDataObject);
        $model = $this->createModel($extendedPaymentDataObject);
        $url = $this->generateUrl($extendedPaymentDataObject);

        return [
            'model' => $model,
            'url' => $url
        ];
    }
}
