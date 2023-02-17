<?php

namespace App\Providers;

use Bentericksen\Payment\Services\Stripe;
use Bentericksen\Payment\Services\Fake\Stripe as FakeStripe;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     * @return void
     */
    public function register()
    {

        $this->app->bind('payment_service', function() { return new Stripe(); });

    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        //
    }
}
