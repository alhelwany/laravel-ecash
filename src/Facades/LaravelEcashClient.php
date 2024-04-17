<?php

namespace Organon\LaravelEcash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Organon\LaravelEcash\Models\EcashPayment checkout(\Organon\LaravelEcash\DataObjects\PaymentDataObject $paymentDataObject)
 * @method static boolean verifyCallbackToken(string $token, string $transactionNo, string $amount, string $orderRef)
 * @see \Organon\LaravelEcash\LaravelEcashClient
 */
class LaravelEcashClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Organon\LaravelEcash\LaravelEcashClient::class;
    }
}
