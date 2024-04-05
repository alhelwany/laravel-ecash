<?php

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Enums\CheckoutType;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\ArrayToUrl;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\PaymentUrlGenerator;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\VerificationCodeGenerator;

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
    new VerificationCodeGenerator(
        $merchantId,
        $merchantSecret
    ),
    $urlEncoder
);

$encodedCallbackUrl = $urlEncoder->encode('http://localhost/ecash/callback');

it('generates proper urls', function () use ($paymentUrlGenerator, $encodedCallbackUrl) {
    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, 420.69);
    $generatedUrl = $paymentUrlGenerator->generateUrl($paymentDataObject);
    expect($generatedUrl)->toBe('https://www.google.com/checkout/Card/12345/54321/D8EC2B657BA3D6DCD9D389DCD9D74B40/SYP/420.69/AR/1/' . $encodedCallbackUrl);
});
