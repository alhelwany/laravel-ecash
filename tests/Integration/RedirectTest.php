<?php

use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Facades\LaravelEcashClient;
use Alhelwany\LaravelEcash\Models\EcashPayment;
use Alhelwany\LaravelEcash\Utilities\UrlEncoder;

use function Pest\Laravel\getJson;

it('can redirect', function () {

    $urlEncoder = new UrlEncoder;
    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $paymentDataObject = new PaymentDataObject($checkoutType, $amount);
    $paymentDataObject->setRedirectUrl('https://www.google.com');

    $model = LaravelEcashClient::checkout($paymentDataObject);
    expect(EcashPayment::first()->status)->toBe(PaymentStatus::PENDING);

    $encodedRedirectUrlFromResult = explode('/', $model['checkout_url'])[12];
    $response = getJson($urlEncoder->decode($encodedRedirectUrlFromResult));
    expect($response->status())->toBe(302);
    $response->assertRedirect('https://www.google.com');
    expect(EcashPayment::first()->status)->toBe(PaymentStatus::PROCESSING);
});
