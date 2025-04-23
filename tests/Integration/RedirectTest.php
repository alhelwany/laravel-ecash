<?php

use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Facades\LaravelEcashClient;
use Alhelwany\LaravelEcash\Models\EcashPayment;

use function Pest\Laravel\getJson;

it('can redirect', function () {

    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $paymentDataObject = new PaymentDataObject($checkoutType, $amount);
    $paymentDataObject->setRedirectUrl('https://www.google.com');

    $model = LaravelEcashClient::checkout($paymentDataObject);
    expect(EcashPayment::first()->status)->toBe(PaymentStatus::PENDING);

    parse_str(parse_url($model['checkout_url'], PHP_URL_QUERY), $params);

    $redirectUrl = $params['ru'];

    $response = getJson($redirectUrl);

    expect($response->status())->toBe(302);
    $response->assertRedirect('https://www.google.com');
    expect(EcashPayment::first()->status)->toBe(PaymentStatus::PROCESSING);
});
