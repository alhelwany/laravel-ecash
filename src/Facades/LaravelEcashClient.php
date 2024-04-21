<?php

namespace Alhelwany\LaravelEcash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Alhelwany\LaravelEcash\Models\EcashPayment checkout(\Alhelwany\LaravelEcash\DataObjects\PaymentDataObject $paymentDataObject)
 * @method static boolean verifyCallbackToken(string $token, string $transactionNo, string $amount, string $orderRef)
 * @see \Alhelwany\LaravelEcash\LaravelEcashClient
 */
class LaravelEcashClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Alhelwany\LaravelEcash\LaravelEcashClient::class;
    }
}
