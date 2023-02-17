<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Bentericksen\StreamdentServices\StreamdentAPIService;
use Bentericksen\StreamdentServices\FakeStreamdentAPIService;

class StreamdentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // If in production bind the real service, otherwise use a fake service
        //if(config('app.env') == "production") {
            $this->app->bind('streamdent_service', function() { return new StreamdentAPIService(); });
        //} else {
        //    $this->app->bind('streamdent_service', function() { return new FakeStreamdentAPIService(); });
        //}
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
