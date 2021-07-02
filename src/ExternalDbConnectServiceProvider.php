<?php

namespace Basduchambre\ExternalDbConnect;

use Illuminate\Support\ServiceProvider;

class ExternalDbConnectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/externaldb.php' => config_path('externaldb.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('externaldbconnect', function () {
            return new ExternalDbConnect();
        });

        $this->commands([
            Commands\Install::class,
            Commands\Generate::class,
        ]);
    }
}
