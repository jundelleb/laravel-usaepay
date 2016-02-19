<?php

namespace PhpUsaepay;

use Illuminate\Support\ServiceProvider;
use PhpUsaepay\Client;

class ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish a copy of configuration file. This will allow users of your package to easily 
        // override the default configuration options
        $this->publishes([
            __DIR__.'/../config/usaepay.php' => config_path('usaepay.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/usaepay.php', 'usaepay'
        );
    }
}
