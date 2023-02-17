<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class SetLastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Login $event
     *
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->last_login = \Carbon\Carbon::now()->toDateTimeString();
        // We only want to update last_login, so we explicitly set timestamps
        // to false to suppress Laravel's default behavior of updating
        // updated_at upon save()-ing, isolated to this invocation.
        $event->user->timestamps = false;
        $event->user->save();
    }
}
