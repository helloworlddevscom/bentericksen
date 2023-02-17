<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Class PaymentService
 * @package App\Facades
 */
class PaymentService extends Facade
{

    /**
     * Gets the registered name of the Stripe service component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment_service';
    }
}
