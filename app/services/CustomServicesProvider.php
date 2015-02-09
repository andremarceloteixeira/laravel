<?php

use Illuminate\Support\ServiceProvider;

class CustomServicesProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        App::bind('check', function() {
            return new Support\Models\Check;
        });
        
        App::bind('helper', function() {
            return new Support\Models\Helper;
        });
        
        App::bind('carbon', function() {
            return new Support\Models\Carbon;
        });
    }

}
