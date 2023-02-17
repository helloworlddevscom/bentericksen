<?php

namespace App\Listeners;

use App\Events\ManagerOwnerCreated;
use App\UserPolicyUpdates;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePolicyNotifications
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
     * @param  ManagerOwnerCreated  $event
     * @return void
     */
    public function handle(ManagerOwnerCreated $event)
    {
        UserPolicyUpdates::select([
            'policy_updater_id',
            'policy_template_update_id',
            'policies',
        ])
        ->join('users', 'users.id', '=', 'user_policy_updates.user_id')
        ->where('users.business_id', $event->user->business_id)
        ->where('user_policy_updates.accepted_at', '0000-00-00 00:00:00')
        ->get()
        ->unique(function ($policyUpdate) {
            return sprintf('%s:%s:%s',
                    $policyUpdate->policy_updater_id,
                    $policyUpdate->policy_template_update_id,
                    $policyUpdate->policies);
        })
        ->each(function ($policyUpdate) use ($event) {
            UserPolicyUpdates::create([
                'user_id' => $event->user->id,
                'policy_updater_id' => $policyUpdate->policy_updater_id,
                'policy_template_update_id' => $policyUpdate->policy_template_update_id,
                'policies' => $policyUpdate->policies,
            ]);
        });
    }
}
