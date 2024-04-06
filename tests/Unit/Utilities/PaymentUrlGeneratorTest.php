<?php

use Organon\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use Organon\LaravelEcash\DataObjects\PaymentDataObject;
use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Utilities\ArrayToUrl;
use Organon\LaravelEcash\Utilities\PaymentUrlGenerator;
use Organon\LaravelEcash\Utilities\UrlEncoder;




it('generates proper urls', function () {

    $urlEncoder = new UrlEncoder;
    $paymentUrlGenerator = new PaymentUrlGenerator(
        config('ecash.gatewayUrl'),
        config('ecash.terminalKey'),
        config('ecash.merchantId'),
        new ArrayToUrl,
        $urlEncoder
    );


    $encodedCallbackUrl = $urlEncoder->encode(route('ecash.callback'));
    $encodedRedirectUrl = $urlEncoder->encode(
        route('ecash.redirect', [
            'paymentId' => 1,
            'token' => '123',
            'redirect_url' => config('app.url')
        ])
    );

    $paymentDataObject = new ExtendedPaymentDataObject(new PaymentDataObject(CheckoutType::CARD, 420.69));
    $paymentDataObject->setVerificationCode('123');
    $paymentDataObject->setId('1');
    $generatedUrl = $paymentUrlGenerator->generateUrl($paymentDataObject);
    expect($generatedUrl)->toBe('https://checkout.ecash-pay.co/checkout/Card/12345/54321/123/SYP/420.69/AR/1/' . $encodedRedirectUrl . '/' . $encodedCallbackUrl);
});
