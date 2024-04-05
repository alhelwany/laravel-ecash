<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MhdGhaithAlhelwany\LaravelEcash\LaravelEcash
 */
class LaravelEcashClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MhdGhaithAlhelwany\LaravelEcash\LaravelEcashClient::class;
    }
}
