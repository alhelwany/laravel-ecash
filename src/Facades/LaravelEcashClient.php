<?php

namespace Organon\LaravelEcash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Organon\LaravelEcash\LaravelEcash
 */
class LaravelEcashClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Organon\LaravelEcash\LaravelEcashClient::class;
    }
}
