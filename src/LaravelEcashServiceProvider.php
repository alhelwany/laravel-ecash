<?php

namespace Alhelwany\LaravelEcash;

use Illuminate\Container\Container;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelEcashServiceProvider extends PackageServiceProvider
{
    /**
     * @param Package $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-ecash')
            ->hasConfigFile()
            ->hasMigration('create_ecash_payments_table')
            ->hasRoute('ecash');

        Container::getInstance()->singleton(LaravelEcashClient::class, function () {
            return LaravelEcashClient::getInstance();
        });
    }
}
