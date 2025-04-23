<?php

use Alhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use Alhelwany\LaravelEcash\Enums\CheckoutType;
use Alhelwany\LaravelEcash\Enums\PaymentStatus;
use Alhelwany\LaravelEcash\Exceptions\EcashServerErrorException;
use Alhelwany\LaravelEcash\Exceptions\InvalidConfigurationException;
use Alhelwany\LaravelEcash\Exceptions\PaymentNotPaidException;
use Alhelwany\LaravelEcash\Facades\LaravelEcashClient;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\postJson;

it('can reverse', function () {

    $amount = 100.12;

    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, $amount);
    $model = LaravelEcashClient::checkout($paymentDataObject);

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";
    postJson(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => true,
        'Message' => $message
    ]);
    $model->refresh();

    Http::fake([
        config('ecash.gatewayUrl') . '/connect/token' => Http::response([
            "access_token" => "Bearer token_token_token",
            "token_type" => "Bearer",
            "expires_in" => 3600,
            "scope" => "TransactionManagementService"
        ], 200),
    ]);

    Http::fake([
        config('ecash.gatewayUrl') . '/api/transaction-managementservice/Public/MerchantTransaction/ReverseTransaction' => Http::response([], 200),
    ]);

    LaravelEcashClient::reverse($model);

    $model->refresh();

    expect($model->status)->toBe(PaymentStatus::REVERSED);
});


it('will throw an exception on reverse when the credentials are incorrect', function () {

    $amount = 100.12;

    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, $amount);
    $model = LaravelEcashClient::checkout($paymentDataObject);

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";
    postJson(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => true,
        'Message' => $message
    ]);
    $model->refresh();

    Http::fake([
        config('ecash.gatewayUrl') . '/connect/token' => Http::response([
            "access_token" => "Bearer token_token_token",
            "token_type" => "Bearer",
            "expires_in" => 3600,
            "scope" => "TransactionManagementService"
        ], 401),
    ]);

    Http::fake([
        config('ecash.gatewayUrl') . '/api/transaction-managementservice/Public/MerchantTransaction/ReverseTransaction' => Http::response([], 200),
    ]);

    LaravelEcashClient::reverse($model);

    $model->refresh();

    expect($model->status)->toBe(PaymentStatus::PAID);

})->throws(InvalidConfigurationException::class);;


it('will throw an exception on reverse when the payment is not paid', function () {

    $amount = 100.12;

    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, $amount);
    $model = LaravelEcashClient::checkout($paymentDataObject);

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";
    
    postJson(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => false,
        'Message' => $message
    ]);
    $model->refresh();

    Http::fake([
        config('ecash.gatewayUrl') . '/connect/token' => Http::response([
            "access_token" => "Bearer token_token_token",
            "token_type" => "Bearer",
            "expires_in" => 3600,
            "scope" => "TransactionManagementService"
        ], 401),
    ]);

    Http::fake([
        config('ecash.gatewayUrl') . '/api/transaction-managementservice/Public/MerchantTransaction/ReverseTransaction' => Http::response([], 200),
    ]);

    LaravelEcashClient::reverse($model);

    $model->refresh();

    expect($model->status)->toBe(PaymentStatus::FAILED);

})->throws(PaymentNotPaidException::class);;


it('will throw an exception on reverse when ecash response is not successful', function () {

    $amount = 100.12;

    $paymentDataObject = new PaymentDataObject(CheckoutType::CARD, $amount);
    $model = LaravelEcashClient::checkout($paymentDataObject);

    $transactionNo = "1";
    $orderRef = $model['id'];
    $message = "success";
    postJson(route('ecash.callback'), [
        'Token' => strtoupper(md5(config()->get('ecash.merchantId') . config()->get('ecash.merchantSecret') . $transactionNo . $amount . $orderRef)),
        'Amount' => $amount,
        'OrderRef' => $orderRef,
        'TransactionNo' => $transactionNo,
        'IsSuccess' => true,
        'Message' => $message
    ]);
    $model->refresh();

    Http::fake([
        config('ecash.gatewayUrl') . '/connect/token' => Http::response([
            "access_token" => "Bearer token_token_token",
            "token_type" => "Bearer",
            "expires_in" => 3600,
            "scope" => "TransactionManagementService"
        ], 200),
    ]);

    Http::fake([
        config('ecash.gatewayUrl') . '/api/transaction-managementservice/Public/MerchantTransaction/ReverseTransaction' => Http::response([], 500),
    ]);

    LaravelEcashClient::reverse($model);

    $model->refresh();

    expect($model->status)->toBe(PaymentStatus::PAID);

})->throws(EcashServerErrorException::class);;
