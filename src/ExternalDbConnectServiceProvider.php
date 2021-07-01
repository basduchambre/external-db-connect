<?php

namespace Basduchambre\ExternalDbConnect;

use Illuminate\Support\ServiceProvider;

class ExternalDbConnectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/externaldbconnect.php' => config_path('externaldbconnect.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register()
    {
        $this->app->bind('externaldbconnect', function () {
            return new ExternalDbConnect();
        });
    }
}
