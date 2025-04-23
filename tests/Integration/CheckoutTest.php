<?php

use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Enums\Currency;
use Alhelwany\LaravelEcash\Enums\Lang;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Exceptions\InvalidAmountException;
use Alhelwany\LaravelEcash\Exceptions\InvalidConfigurationException;
use Alhelwany\LaravelEcash\Facades\LaravelEcashClient;
use Alhelwany\LaravelEcash\Models\EcashPayment;


it('can checkout', function () {


    $encodedCallbackUrl = urlencode(route('ecash.callback'));

    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));


    $token = strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $amount . $model->id));
    $modelId = $model['id'];

    $encodedRedirectUrl = urlencode(
        route('ecash.redirect', [
            'paymentId' => $modelId,
            'token' => $token,
            'redirect_url' => config('app.url')
        ])
    );


    expect($model['checkout_url'])->toBe("https://checkout.ecash-pay.co/checkout/QR?tk=12345&mid=54321&vc=$token&c=SYP&a=10.1&lang=AR&or=$modelId&ru=$encodedRedirectUrl&cu=$encodedCallbackUrl");

    expect($model['amount'])->toBe($amount);
    expect($model['checkout_type'])->toBe($checkoutType);
    expect($model['status'])->toBe(PaymentStatus::PENDING);
    expect($model['verification_code'])->toBe($token);
    expect($model['currency'])->toBe(Currency::SYP);


    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, $amount);
    $paymentDataObject->setLang(Lang::EN);
    $paymentDataObject->setRedirectUrl('https://www.google.com');

    $model = LaravelEcashClient::checkout($paymentDataObject);

    $token = strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $amount . $model->id));
    $modelId = $model['id'];

    $encodedRedirectUrl = urlencode(
        route('ecash.redirect', [
            'paymentId' => $modelId,
            'token' => $token,
            'redirect_url' => 'https://www.google.com'
        ])
    );

    expect($model['checkout_url'])->toBe("https://checkout.ecash-pay.co/checkout/Card?tk=12345&mid=54321&vc=$token&c=SYP&a=10.1&lang=EN&or=$modelId&ru=$encodedRedirectUrl&cu=$encodedCallbackUrl");

    expect(EcashPayment::count())->toBe(2);
});


it('throws exception when using checkout with invalid amount', function () {
    $checkoutType = CheckoutType::QR;
    $amount = -10.10;
    LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));
})->throws(InvalidAmountException::class);


it('throws exception when using checkout before setting up the configurations', function () {
    config()->set('ecash.merchantId', null);
    LaravelEcashClient::checkout(new PaymentDataObject(CheckoutType::QR, 10));
})->throws(InvalidConfigurationException::class);
