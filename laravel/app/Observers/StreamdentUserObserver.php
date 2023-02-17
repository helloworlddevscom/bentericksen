<?php

namespace App\Observers;

use App\User;
use App\Jobs\PerformStreamdentUserProcess;
use Illuminate\Support\Facades\Log;

class StreamdentUserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        /*
         * This prevents a PerformStreamdentUserProcess::createStreamdentUser() job from being queued if either
         * User's Business hasn't been created yet or the User's Business isn't SOP enabled.
         */
        if (!isset($user->business) || !$user->business->sop_active) {
          Log::info("StreamdentUserObserver - [create] user has no business or business sop not active user: {$user->id}");
          return;
        }

        PerformStreamdentUserProcess::dispatch($user, 'create');
    }

    /**
     * Listen to the User updated event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
      if (!in_array('terminated', array_keys($user->getDirty()))) {
        return;
      }

      /*
        * This prevents a PerformStreamdentUserProcess::updateStreamdentUser() job from being queued if either
        * User's Business hasn't been created yet or the User's Business isn't SOP enabled.
        */
      if (!isset($user->business) || !$user->business->sop_active) {
        Log::info("StreamdentUserObserver - [update] user has no business or business sop not active user: {$user->id}");
        return;
      }
      
      if (!$user->streamdentUser) {
        PerformStreamdentUserProcess::dispatch($user, 'create');
        return;
      }

      PerformStreamdentUserProcess::dispatch($user, 'toggle');
        
    }

    /**
     * Listen to the User deleted event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
      if (!$user->streamdentUser || !isset($user->business) || !$user->business->sop_active) {
        Log::info("StreamdentUserObserver - [delete] user has no business or business sop not active {$user->id}");
        return;
      }

      PerformStreamdentUserProcess::dispatch($user, 'disable');
    }
}
