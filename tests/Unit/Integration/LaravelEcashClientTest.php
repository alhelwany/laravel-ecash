<?php

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Enums\CheckoutType;
use MhdGhaithAlhelwany\LaravelEcash\Enums\Currency;
use MhdGhaithAlhelwany\LaravelEcash\Enums\Lang;
use MhdGhaithAlhelwany\LaravelEcash\Enums\PaymentStatus;
use MhdGhaithAlhelwany\LaravelEcash\LaravelEcashClient;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;

$gatewayUrl = "www.example.com";
$gatewayUrl = 'https://www.google.com';
$terminalKey = '12345';
$merchantId = '54321';
$merchantSecret = 'FDSj2134PiewcczS3';
$urlEncoder = new UrlEncoder;

$encodedCallbackUrl = $urlEncoder->encode('http://localhost/ecash/callback');

$laravelEcashClient = new LaravelEcashClient($gatewayUrl, $terminalKey, $merchantId, $merchantSecret);

it('can checkout', function () use ($laravelEcashClient, $encodedCallbackUrl) {
    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $result = $laravelEcashClient->checkout(new PaymentDataObject($checkoutType, $amount));
    $this->expect($result['url'])->toBe('https://www.google.com/checkout/QR/12345/54321/068391DCF5AA8CF7CDB26092C43CA6FE/SYP/10.1/AR/1/' . $encodedCallbackUrl);
    $this->expect($result['model']['amount'])->toBe($amount);
    $this->expect($result['model']['checkout_type'])->toBe($checkoutType);
    $this->expect($result['model']['status'])->toBe(PaymentStatus::PENDING);
    $this->expect($result['model']['verification_code'])->toBe('068391DCF5AA8CF7CDB26092C43CA6FE');
    $this->expect($result['model']['currency'])->toBe(Currency::SYP);


    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, $amount);
    $paymentDataObject->setLang(Lang::EN);
    $paymentDataObject->setRedirectUrl('localhost');
    $result = $laravelEcashClient->checkout($paymentDataObject);
    $this->expect($result['url'])->toBe('https://www.google.com/checkout/Card/12345/54321/9AEAC03A4EFD003D036DB05F658816B5/SYP/10.1/EN/2/localhost/' . $encodedCallbackUrl);
    $this->expect(EcashPayment::count())->toBe(2);
});
