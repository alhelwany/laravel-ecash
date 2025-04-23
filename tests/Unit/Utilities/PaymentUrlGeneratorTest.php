<?php

use Alhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Utilities\ArrayToUrl;
use Alhelwany\LaravelEcash\Utilities\PaymentUrlGenerator;




it('generates proper urls', function () {

    $paymentUrlGenerator = new PaymentUrlGenerator(
        config('ecash.gatewayUrl'),
        config('ecash.terminalKey'),
        config('ecash.merchantId'),
        new ArrayToUrl,
    );


    $encodedCallbackUrl = urlencode(route('ecash.callback'));

    $encodedRedirectUrl = urlencode(
        route('ecash.redirect', [
            'paymentId' => 1,
            'token' => '123',
            'redirect_url' => config('app.url')
        ])
    );

    $paymentDataObject = new ExtendedPaymentDataObject(new PaymentDataObject(CheckoutType::CARD, 420.69));
    $paymentDataObject->setVerificationCode('123');
    $paymentDataObject->setId(1);

    $generatedUrl = $paymentUrlGenerator->generateUrl($paymentDataObject);

    expect($generatedUrl)->toBe('https://checkout.ecash-pay.co/checkout/Card?tk=12345&mid=54321&vc=123&c=SYP&a=420.69&lang=AR&or=1&ru=' . $encodedRedirectUrl . '&cu=' . $encodedCallbackUrl);
});
