<?php

namespace GeorgeTheNerd\LaravelEcash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GeorgeTheNerd\LaravelEcash\LaravelEcash
 */
class LaravelEcashClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \GeorgeTheNerd\LaravelEcash\LaravelEcashClient::class;
    }
}
