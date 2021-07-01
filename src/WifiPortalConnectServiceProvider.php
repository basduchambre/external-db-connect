<?php

namespace Basduchambre\JuniperMist;

use Illuminate\Support\ServiceProvider;

class JuniperMistServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/junipermist.php' => config_path('junipermist.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register()
    {
        $this->app->bind('junipermist', function () {
            return new JuniperMistClients();
        });
    }
}
