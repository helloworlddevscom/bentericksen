<?php

namespace App\Events;

use App\Events\UserLoggedIn;
use App\User;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;

class CheckLicenseAgreement
{
    /**
     * Create the event handler.
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
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function handle(User $user, $remember)
    {
        return view('user.index');
        dd($user);
    }
}
