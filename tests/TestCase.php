<?php

namespace Souidev\ClicToPayLaravel\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Souidev\ClicToPayLaravel\ClicToPayLaravelServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Souidev\\ClicToPayLaravel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ClicToPayLaravelServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // Set up test configuration
        config()->set('clictopay', [
            'username' => 'test_user',
            'password' => 'test_pass',
            'test_mode' => true,
            'return_url' => 'https://example.com/return',
            'fail_url' => 'https://example.com/fail',
            'api_base_url' => 'https://test.clictopay.com/payment/rest/',
        ]);
    }
}
