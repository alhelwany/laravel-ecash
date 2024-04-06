<?php

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Enums\CheckoutType;
use MhdGhaithAlhelwany\LaravelEcash\Enums\PaymentStatus;
use MhdGhaithAlhelwany\LaravelEcash\Facades\LaravelEcashClient;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;


it('can redirect', function () {

    $urlEncoder = new UrlEncoder;
    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $paymentDataObject = new PaymentDataObject($checkoutType, $amount);
    $paymentDataObject->setRedirectUrl('https://www.google.com');

    $result = LaravelEcashClient::checkout($paymentDataObject);
    expect(EcashPayment::first()->status)->toBe(PaymentStatus::PENDING);

    $encodedRedirectUrlFromResult = explode('/', $result['url'])[12];
    $response = $this->get($urlEncoder->decode($encodedRedirectUrlFromResult));
    expect($response->status())->toBe(302);
    $response->assertRedirect('https://www.google.com');
    expect(EcashPayment::first()->status)->toBe(PaymentStatus::PROCESSING);
});
