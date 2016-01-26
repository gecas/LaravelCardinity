<?php

namespace Artme\Cardinity;

use Illuminate\Support\ServiceProvider;

include __DIR__.'/routes.php';

class CardinityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/cardinity.php' => config_path('cardinity.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Artme\Cardinity\CardinityController');
//        require_once(__DIR__.'/../lib/WebToPay.php');
    }
}
