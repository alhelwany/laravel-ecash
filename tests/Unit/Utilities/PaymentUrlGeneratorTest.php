<?php

use GeorgeTheNerd\LaravelEcash\DataObjects\PaymentDataObject;
use GeorgeTheNerd\LaravelEcash\Enums\CheckoutType;
use GeorgeTheNerd\LaravelEcash\Utilities\ArrayToUrl;
use GeorgeTheNerd\LaravelEcash\Utilities\PaymentUrlGenerator;
use GeorgeTheNerd\LaravelEcash\Utilities\UrlEncoder;
use GeorgeTheNerd\LaravelEcash\Utilities\VerificationCodeGenerator;

$gatewayUrl = "https://www.google.com";
$terminalKey = "12345";
$merchantId = "54321";
$merchantSecret = "FDSj2134PiewcczS3";
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
