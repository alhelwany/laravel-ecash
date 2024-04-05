<?php

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\ExtendedPaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Enums\CheckoutType;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\ArrayToUrl;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\PaymentUrlGenerator;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;

$gatewayUrl = 'https://www.google.com';
$terminalKey = '12345';
$merchantId = '54321';
$merchantSecret = 'FDSj2134PiewcczS3';
$urlEncoder = new UrlEncoder;

$paymentUrlGenerator = new PaymentUrlGenerator(
    $gatewayUrl,
    $terminalKey,
    $merchantId,
    new ArrayToUrl,
    $urlEncoder
);

$encodedCallbackUrl = $urlEncoder->encode('http://localhost/ecash/callback');

it('generates proper urls', function () use ($paymentUrlGenerator, $encodedCallbackUrl) {
    $paymentDataObject = new ExtendedPaymentDataObject(new PaymentDataObject(CheckoutType::CARD, 420.69));
    $paymentDataObject->setVerificationCode('123');
    $paymentDataObject->setId('1');
    $generatedUrl = $paymentUrlGenerator->generateUrl($paymentDataObject);
    expect($generatedUrl)->toBe('https://www.google.com/checkout/Card/12345/54321/123/SYP/420.69/AR/1/' . $encodedCallbackUrl);
});
