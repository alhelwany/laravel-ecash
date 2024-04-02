<?php

namespace GeorgeTheNerd\LaravelEcash;

use Illuminate\Container\Container;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelEcashServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-ecash')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-ecash_table')
            ->hasRoute('ecash');

        Container::getInstance()->singleton(LaravelEcashClient::class, function () {
            return LaravelEcashClient::getInstance();
        });
    }
}
