<?php

namespace Alhelwany\LaravelEcash\Tests;

use Alhelwany\LaravelEcash\LaravelEcashServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Alhelwany\\LaravelEcash\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelEcashServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.debug', false);
        config()->set('ecash.terminalKey', '12345');
        config()->set('ecash.merchantId', '54321');
        config()->set('ecash.merchantSecret', 'FDSj2134PiewcczS3');
        config()->set('app.url', 'https://laravel-ecash-test.com');
        URL::forceRootUrl('https://laravel-ecash-test.com');

        $migration = include __DIR__ . '/../database/migrations/create_ecash_payments_table.php.stub';
        $migration->up();
    }
}
