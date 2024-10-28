<?php

namespace destrompesa\mpesa;

use Illuminate\Support\ServiceProvider;

class MpesaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files or migrations, if applicable
        $this->publishes([
            __DIR__.'/config/mpesa.php' => config_path('mpesa.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Register any bindings or services for the package
        $this->app->singleton('mpesa', function () {
            return new Mpesa(); // Replace Mpesa with your main class
        });
    }
}
