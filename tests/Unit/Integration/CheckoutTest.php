<?php

use Organon\LaravelEcash\DataObjects\PaymentDataObject;
use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Enums\Currency;
use Organon\LaravelEcash\Enums\Lang;
use Organon\LaravelEcash\Enums\PaymentStatus;
use Organon\LaravelEcash\Facades\LaravelEcashClient;
use Organon\LaravelEcash\Models\EcashPayment;
use Organon\LaravelEcash\Utilities\UrlEncoder;


it('can checkout', function () {

    $urlEncoder = new UrlEncoder;

    $encodedCallbackUrl = $urlEncoder->encode(route('ecash.callback'));
    $encodedRedirectUrl = $urlEncoder->encode(
        route('ecash.redirect', [
            'paymentId' => 1,
            'token' => '068391DCF5AA8CF7CDB26092C43CA6FE',
            'redirect_url' => config('app.url')
        ])
    );
    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $this->expect($model['checkout_url'])->toBe('https://checkout.ecash-pay.co/checkout/QR/12345/54321/068391DCF5AA8CF7CDB26092C43CA6FE/SYP/10.1/AR/1/' . $encodedRedirectUrl . '/' . $encodedCallbackUrl);
    $this->expect($model['amount'])->toBe($amount);
    $this->expect($model['checkout_type'])->toBe($checkoutType);
    $this->expect($model['status'])->toBe(PaymentStatus::PENDING);
    $this->expect($model['verification_code'])->toBe('068391DCF5AA8CF7CDB26092C43CA6FE');
    $this->expect($model['currency'])->toBe(Currency::SYP);


    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, $amount);
    $paymentDataObject->setLang(Lang::EN);

    $encodedRedirectUrl = $urlEncoder->encode(
        route('ecash.redirect', [
            'paymentId' => 2,
            'token' => '9AEAC03A4EFD003D036DB05F658816B5',
            'redirect_url' => 'https://www.google.com'
        ])
    );
    $paymentDataObject->setRedirectUrl('https://www.google.com');

    $model = LaravelEcashClient::checkout($paymentDataObject);
    $this->expect($model['checkout_url'])->toBe('https://checkout.ecash-pay.co/checkout/Card/12345/54321/9AEAC03A4EFD003D036DB05F658816B5/SYP/10.1/EN/2/' . $encodedRedirectUrl . '/' . $encodedCallbackUrl);
    $this->expect(EcashPayment::count())->toBe(2);
});
