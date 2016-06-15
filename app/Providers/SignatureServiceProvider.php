<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/13/15
 * Time: 3:32 PM
 */



namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services as Services;

class SignatureServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'LDAPService',
            'App\Services\LDAPService'
        );

        //SVG conversion library
        $this->app->bind(
            'SVGConvert',
            'App\Services\SVGConversion\SVGConvert'
        );
    }

}

