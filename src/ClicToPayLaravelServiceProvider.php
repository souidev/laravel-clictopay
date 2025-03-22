<?php

namespace Souidev\ClicToPayLaravel;

use Illuminate\Support\ServiceProvider;

class ClicToPayLaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/clictopay.php', 'clictopay');

        // Bind the main service class to the IoC container
        $this->app->bind('ClicToPay', function ($app) {
            return new ClicToPayService($app['config']->get('clictopay'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/clictopay.php' => config_path('clictopay.php'),
        ], 'config');
    }
}
