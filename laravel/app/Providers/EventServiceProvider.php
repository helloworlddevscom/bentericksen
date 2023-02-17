<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // https://laravel.com/docs/5.6/authentication#events
        'Illuminate\Auth\Events\Login' => [
            \App\Listeners\SetLastLogin::class,
        ],
        \App\Events\ManagerOwnerCreated::class => [
            \App\Listeners\CreatePolicyNotifications::class,
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
