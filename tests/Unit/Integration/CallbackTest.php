<?php

use Organon\LaravelEcash\DataObjects\PaymentDataObject;
use Organon\LaravelEcash\Enums\CheckoutType;
use Organon\LaravelEcash\Enums\PaymentStatus;
use Organon\LaravelEcash\Facades\LaravelEcashClient;

it('can callback with success', function () {

    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";

    $response = $this->post(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => true,
        'Message' => $message
    ]);

    $this->expect($response->status())->toBe(200);

    $model->refresh();

    $this->expect($model->status)->toBe(PaymentStatus::PAID);
    $this->expect($model->transaction_no)->toBe($transactionNo);
    $this->expect($model->message)->toBe($message);
});


it('can callback with failure', function () {

    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";

    $response = $this->post(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => false,
        'Message' => $message
    ]);

    $this->expect($response->status())->toBe(200);

    $model->refresh();

    $this->expect($model->status)->toBe(PaymentStatus::FAILED);
    $this->expect($model->transaction_no)->toBe($transactionNo);
    $this->expect($model->message)->toBe($message);
});

it('can\'t callback with invalid token', function () {

    $checkoutType = CheckoutType::QR;
    $amount = 10.10;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";

    $response = $this->post(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef . "Hello World")),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => false,
        'Message' => $message
    ]);

    $this->expect($response->status())->toBe(302);

    $model->refresh();

    $this->expect($model->status)->toBe(PaymentStatus::PENDING);
    $this->expect($model->transaction_no)->toBe(null);
    $this->expect($model->message)->toBe(null);
});
