<?php

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Enums\CheckoutType;
use MhdGhaithAlhelwany\LaravelEcash\Enums\Currency;
use MhdGhaithAlhelwany\LaravelEcash\Enums\Lang;
use MhdGhaithAlhelwany\LaravelEcash\Enums\PaymentStatus;
use MhdGhaithAlhelwany\LaravelEcash\Facades\LaravelEcashClient;
use MhdGhaithAlhelwany\LaravelEcash\Models\EcashPayment;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;


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
    $result = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $result['model']->refresh();
    $this->expect($result['url'])->toBe('https://checkout.ecash-pay.co/checkout/QR/12345/54321/068391DCF5AA8CF7CDB26092C43CA6FE/SYP/10.1/AR/1/' . $encodedRedirectUrl . '/' . $encodedCallbackUrl);
    $this->expect($result['model']['amount'])->toBe($amount);
    $this->expect($result['model']['checkout_type'])->toBe($checkoutType);
    $this->expect($result['model']['status'])->toBe(PaymentStatus::PENDING);
    $this->expect($result['model']['verification_code'])->toBe('068391DCF5AA8CF7CDB26092C43CA6FE');
    $this->expect($result['model']['currency'])->toBe(Currency::SYP);


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

    $result = LaravelEcashClient::checkout($paymentDataObject);
    $this->expect($result['url'])->toBe('https://checkout.ecash-pay.co/checkout/Card/12345/54321/9AEAC03A4EFD003D036DB05F658816B5/SYP/10.1/EN/2/' . $encodedRedirectUrl . '/' . $encodedCallbackUrl);
    $this->expect(EcashPayment::count())->toBe(2);
});
