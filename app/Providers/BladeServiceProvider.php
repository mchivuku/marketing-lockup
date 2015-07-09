<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/22/15
 * Time: 1:39 PM
 */

 namespace App\Providers;

 use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @param  \Illuminate\Bus\Dispatcher  $dispatcher
     * @return void
     */
    public function boot()
    {
        \Blade::extend(function($value)
        {
            return preg_replace('/(\s*)@break(\s*)/', '$1<?php break; ?>$2', $value);
        });



    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
