<?php

use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Facades\LaravelEcashClient;

use function Pest\Laravel\postJson;

it('can callback with success', function () {

    $checkoutType = CheckoutType::QR;
    $amount = 10.11;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";

    $response = postJson(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => true,
        'Message' => $message
    ]);

    expect($response->status())->toBe(200);

    $model->refresh();

    expect($model->status)->toBe(PaymentStatus::PAID);
    expect($model->transaction_no)->toBe($transactionNo);
    expect($model->message)->toBe($message);
});


it('can callback with failure', function () {

    $checkoutType = CheckoutType::QR;
    $amount = 10.11;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";

    $response = postJson(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => false,
        'Message' => $message
    ]);

    expect($response->status())->toBe(200);

    $model->refresh();

    expect($model->status)->toBe(PaymentStatus::FAILED);
    expect($model->transaction_no)->toBe($transactionNo);
    expect($model->message)->toBe($message);
});

it('can\'t callback with invalid token', function () {

    $checkoutType = CheckoutType::QR;
    $amount = 10.11;
    $model = LaravelEcashClient::checkout(new PaymentDataObject($checkoutType, $amount));

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";

    $response = postJson(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef . "Hello World")),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => false,
        'Message' => $message
    ]);

    expect($response->status())->toBe(422);

    $model->refresh();

    expect($model->status)->toBe(PaymentStatus::PENDING);
    expect($model->transaction_no)->toBe(null);
    expect($model->message)->toBe(null);
});
