<?php

namespace App\Observers;

use App\Business;
use App\Jobs\PerformStreamdentClientProcess;
use App\Jobs\PerformStreamdentUserProcess;
use App\StreamdentClient;
use App\StreamdentUser;
use Illuminate\Support\Facades\Log;

class StreamdentBusinessObserver
{
    /**
     * Listen to the Business created event.
     *
     * @param  \App\Business  $business
     * @return void
     */
    public function created(Business $business): void
    {
        /*
         * This prevents a PerformStreamdentClientProcess::createStreamdentClient() job from being queued if the
         * Business isn't SOP enabled.
         */
        if (!$business->sop_active) {
          return;
        }

        $this->enableSop($business);
    }

    /**
     * Listen to the Business updated event.
     *
     * @param  \App\Business  $business
     * @return void
     */
    public function updated(Business $business): void
    {
      $keys = array_keys($business->getDirty());
      $enable_sop = in_array('enable_sop', $keys);
      $status = in_array('status', $keys);

      if (!$enable_sop && !$status) {
        Log::info("StreamdentBusinessObserver - [update] skipped due to dirty keys filter for business {$business->id} keys");
        return;
      }

      /*
      * This prevents a PerformStreamdentClientProcess::updateStreamdentClient() job from being queued if the
      * Business isn't SOP enabled.
      */
      if (!$business->sop_active) {
        $this->disableSop($business);
        return;
      }
       /*
        * This queues the PerformStreamdentClientProcess::createStreamdentClient() job if it wasn't previously queued due to
        * the Business not having been SOP enabled at the time of Business creation.
        */
       /*
        * This queues the PerformStreamdentUserProcess::createStreamdentUser() job if it wasn't previously queued due to
        * the User's Business not having been created at the time of User creation or due to the User's Business not having
        * been SOP enabled at the time of User creation.
        */
      $this->enableSop($business);
    }

    /**
     * Listen to the Business deleted event.
     *
     * @param  \App\Business  $business
     * @return void
     */
    public function deleted(Business $business): void
    {
      PerformStreamdentClientProcess::dispatch($business, 'disable');
    }

    protected function enableSop(Business $business): void
    {
      $jobs = [];

      if ($business->contactUser) {
        $jobs[] = new PerformStreamdentUserProcess($business->contactUser, $business->contactUser->streamdentUser ? 'toggle' : 'create');
      } else {
        Log::info("No primary user loaded so skipping sop enable for user on business {$business->id}");
      }

      if (empty(count($business->hiredUsers))) {
        Log::info("No hired users loaded so skipping sop enable for users on business {$business->id}");
      }

      $business->hiredUsers->each(function ($user) use ($business) {
        $jobs[] = new PerformStreamdentUserProcess($user, $user->streamdentUser ? 'toggle' : 'create');
      });

      PerformStreamdentClientProcess::withChain($jobs)->dispatch($business, 'toggle');
    }

    protected function disableSop(Business $business): void
    {
      if ($business->streamdentClient) {
        PerformStreamdentClientProcess::dispatch($business, 'toggle');
      }
      
      if ($business->contactUser->streamdentUser) {
        PerformStreamdentUserProcess::dispatch($business->contactUser, 'disable');
      }

      $business->hiredUsers->each(function ($user) use ($business) {
        if (!$user->streamdentUser) {
          return;
        }
        PerformStreamdentUserProcess::dispatch($user, 'disable');
      });
    }
}
