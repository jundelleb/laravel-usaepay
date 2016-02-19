<?php

namespace PhpUsaepay;

use Illuminate\Support\ServiceProvider as AppServiceProvider;
use PhpUsaepay\Client;

class ServiceProvider extends AppServiceProvider
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
        $this->package('jundelleb/laravel-usaepay');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register our config
        $this->app['config']->package('jundelleb/laravel-usaepay', __DIR__.'/../config/usaepay.php');
    }
}
