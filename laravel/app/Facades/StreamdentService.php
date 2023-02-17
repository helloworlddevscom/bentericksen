<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Class StreamdentService
 * @package App\Facades
 */
class StreamdentService extends Facade
{

    /**
     * Gets the registered name of the Streamdent service component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'streamdent_service';
    }
}
