<?php

namespace App\Policies;

use App\BonusPro\Plan;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Authorization policy for BonusPro models.
 */
class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can see a given plan.
     *
     * @param  \App\User  $user
     * @param  \App\BonusPro\Plan  $plan
     * @return bool
     */
    public function view(User $user, Plan $plan)
    {
        $viewAs = session()->get('viewAs');
        if (! empty($viewAs)) {
            $user = User::find(session()->get('viewAs'));
        }

        return $user->business_id === $plan->business_id;
    }

    /**
     * Determine if the given plan can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\BonusPro\Plan  $plan
     * @return bool
     */
    public function update(User $user, Plan $plan)
    {
        $viewAs = session()->get('viewAs');
        if (! empty($viewAs)) {
            $user = User::find(session()->get('viewAs'));
        }

        return $user->business_id === $plan->business_id;
    }

    /**
     * Determine if the given plan can be deleted by the user.
     *
     * @param  \App\User  $user
     * @param  \App\BonusPro\Plan  $plan
     * @return bool
     */
    public function delete(User $user, Plan $plan)
    {
        $viewAs = session()->get('viewAs');
        if (! empty($viewAs)) {
            $user = User::find(session()->get('viewAs'));
        }

        return $user->business_id === $plan->business_id;
    }
}
