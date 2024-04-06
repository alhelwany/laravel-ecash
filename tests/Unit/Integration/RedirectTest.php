<?php

use Organon\LaravelEcash\DataObjects\PaymentDataObject;
use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Enums\PaymentStatus;
use Organon\LaravelEcash\Facades\LaravelEcashClient;
use Organon\LaravelEcash\Models\EcashPayment;
use Organon\LaravelEcash\Utilities\UrlEncoder;


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
